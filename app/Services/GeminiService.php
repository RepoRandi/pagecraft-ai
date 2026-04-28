<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    private string $apiKey;
    private string $model;
    private int $maxOutputTokens;
    private int $timeout;
    private bool $debug;

    public function __construct()
    {
        $this->apiKey = (string) config('services.gemini.api_key');
        $this->model = (string) config('services.gemini.model', 'gemini-2.5-flash');
        $this->maxOutputTokens = (int) config('services.gemini.max_output_tokens', 1800);
        $this->timeout = (int) config('services.gemini.timeout', 30);
        $this->debug = (bool) config('services.gemini.debug', false);
    }

    public function generateSalesPage(array $data): array
    {
        $cacheKey = 'gemini_sales_page_' . md5(json_encode($data));

        return Cache::remember($cacheKey, now()->addDays(7), function () use ($data) {
            return $this->callGemini($data);
        });
    }

    public function regenerateSection(array $currentContent, array $salesPageData, string $section): array
    {
        $allowedSections = ['headline', 'cta', 'benefits'];

        if (!in_array($section, $allowedSections, true)) {
            return $currentContent;
        }

        try {
            $prompt = $this->buildRegeneratePrompt($currentContent, $salesPageData, $section);

            $result = $this->requestGemini($prompt);

            if (!$result) {
                return $currentContent;
            }

            if ($section === 'headline' && !empty($result['headline'])) {
                $currentContent['headline'] = $result['headline'];
                $currentContent['subheadline'] = $result['subheadline'] ?? ($currentContent['subheadline'] ?? '');
            }

            if ($section === 'cta' && !empty($result['cta'])) {
                $currentContent['cta'] = $result['cta'];
            }

            if ($section === 'benefits' && !empty($result['benefits'])) {
                $currentContent['benefits'] = $result['benefits'];
            }

            $currentContent['_source'] = 'gemini_ai_regenerated';

            return $this->normalizeContent($currentContent, $salesPageData);
        } catch (\Throwable $e) {
            Log::warning('Gemini regenerate failed', [
                'section' => $section,
                'message' => $e->getMessage(),
            ]);

            return $currentContent;
        }
    }

    private function callGemini(array $data): array
    {
        if (empty($this->apiKey)) {
            Log::warning('Gemini API key is empty. Using fallback content.');

            return $this->fallbackContent($data, 'fallback_no_api_key');
        }

        try {
            $prompt = $this->buildPrompt($data);

            $result = $this->requestGemini($prompt);

            if (!$result) {
                return $this->fallbackContent($data, 'fallback_invalid_response');
            }

            $result['_source'] = 'gemini_ai';

            return $this->normalizeContent($result, $data);
        } catch (\Throwable $e) {
            Log::error('Gemini API failed', [
                'message' => $e->getMessage(),
            ]);

            return $this->fallbackContent($data, 'fallback_exception');
        }
    }

    private function requestGemini(string $prompt): ?array
    {
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent";

        $response = Http::timeout($this->timeout)
            ->retry(1, 500)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->post($url . '?key=' . $this->apiKey, [
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            [
                                'text' => $prompt,
                            ],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'topP' => 0.9,
                    'maxOutputTokens' => $this->maxOutputTokens,
                    'responseMimeType' => 'application/json',
                ],
            ]);

        if (!$response->successful()) {
            Log::warning('Gemini API response failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        }

        $text = $response->json('candidates.0.content.parts.0.text');

        if (!$text) {
            Log::warning('Gemini empty text response', [
                'response' => $response->json(),
            ]);

            return null;
        }

        $cleanJson = $this->extractJson($text);
        $decoded = json_decode($cleanJson, true);

        if (!is_array($decoded)) {
            Log::warning('Gemini invalid JSON', [
                'raw_text' => $text,
                'json_error' => json_last_error_msg(),
            ]);

            return null;
        }

        if ($this->debug) {
            Log::info('Gemini success', [
                'model' => $this->model,
                'result_keys' => array_keys($decoded),
            ]);
        }

        return $decoded;
    }

    private function buildPrompt(array $data): string
    {
        $productName = $data['product_name'] ?? '';
        $description = $data['description'] ?? '';
        $features = $data['features'] ?? '';
        $targetAudience = $data['target_audience'] ?? '';
        $price = $data['price'] ?? '';
        $uniqueSellingPoints = $data['unique_selling_points'] ?? '';

        return <<<PROMPT
Generate a concise, persuasive AI sales page in valid JSON only.

Product: {$productName}
Description: {$description}
Features: {$features}
Target audience: {$targetAudience}
Price: {$price}
Unique selling points: {$uniqueSellingPoints}

Return only this JSON structure:
{
  "headline": "",
  "subheadline": "",
  "description": "",
  "benefits": [
    {"title": "", "description": ""},
    {"title": "", "description": ""},
    {"title": "", "description": ""}
  ],
  "features": [
    {"title": "", "description": ""},
    {"title": "", "description": ""},
    {"title": "", "description": ""}
  ],
  "social_proof": {
    "title": "",
    "description": ""
  },
  "pricing": {
    "label": "",
    "price": "",
    "description": ""
  },
  "cta": {
    "text": "",
    "button": ""
  }
}

Rules:
- JSON only.
- No markdown.
- No explanation.
- Keep copy short, clear, and conversion-focused.
- Make the headline specific to the product and target audience.
- Benefits must explain real value, not generic filler.
- Features must be based on the provided features.
- CTA button must be short and action-oriented.
PROMPT;
    }

    private function buildRegeneratePrompt(array $currentContent, array $salesPageData, string $section): string
    {
        $productName = $salesPageData['product_name'] ?? '';
        $description = $salesPageData['description'] ?? '';
        $features = $salesPageData['features'] ?? '';
        $targetAudience = $salesPageData['target_audience'] ?? '';
        $price = $salesPageData['price'] ?? '';
        $uniqueSellingPoints = $salesPageData['unique_selling_points'] ?? '';
        $currentJson = json_encode($currentContent, JSON_PRETTY_PRINT);

        return <<<PROMPT
Regenerate only the "{$section}" section for this sales page.

Product: {$productName}
Description: {$description}
Features: {$features}
Target audience: {$targetAudience}
Price: {$price}
Unique selling points: {$uniqueSellingPoints}

Current content:
{$currentJson}

Return valid JSON only.

If section is headline, return:
{
  "headline": "",
  "subheadline": ""
}

If section is cta, return:
{
  "cta": {
    "text": "",
    "button": ""
  }
}

If section is benefits, return:
{
  "benefits": [
    {"title": "", "description": ""},
    {"title": "", "description": ""},
    {"title": "", "description": ""}
  ]
}

Rules:
- JSON only.
- No markdown.
- No explanation.
- Make it more persuasive and specific.
PROMPT;
    }

    private function extractJson(string $text): string
    {
        $text = trim($text);

        $text = preg_replace('/^```json\s*/', '', $text);
        $text = preg_replace('/^```\s*/', '', $text);
        $text = preg_replace('/\s*```$/', '', $text);

        $start = strpos($text, '{');
        $end = strrpos($text, '}');

        if ($start !== false && $end !== false && $end > $start) {
            return substr($text, $start, $end - $start + 1);
        }

        return $text;
    }

    private function normalizeContent(array $content, array $data): array
    {
        $content['headline'] = $content['headline']
            ?? 'Launch ' . ($data['product_name'] ?? 'Your Product') . ' With Clearer Messaging';

        $content['subheadline'] = $content['subheadline']
            ?? 'A focused sales page for ' . ($data['target_audience'] ?? 'your target audience') . '.';

        $content['description'] = $content['description']
            ?? ($data['description'] ?? '');

        $content['benefits'] = $this->normalizeList($content['benefits'] ?? [], [
            [
                'title' => 'Clearer Product Positioning',
                'description' => 'Turn product details into simple, persuasive messaging.',
            ],
            [
                'title' => 'Faster Landing Page Creation',
                'description' => 'Generate a structured page without writing from scratch.',
            ],
            [
                'title' => 'Audience-Focused Copy',
                'description' => 'Highlight value based on your target audience.',
            ],
        ]);

        $content['features'] = $this->normalizeList($content['features'] ?? [], $this->fallbackFeatures($data));

        $content['social_proof'] = $content['social_proof'] ?? [
            'title' => 'Designed for Practical Product Teams',
            'description' => 'Built to support faster copywriting and landing page creation.',
        ];

        $content['pricing'] = $content['pricing'] ?? [
            'label' => 'Simple Pricing',
            'price' => $data['price'] ?? '',
            'description' => 'Start with a clear and transparent plan.',
        ];

        $content['cta'] = $content['cta'] ?? [
            'text' => 'Ready to turn your product into a polished offer?',
            'button' => 'Create My Page',
        ];

        return $content;
    }

    private function normalizeList(array $items, array $fallback): array
    {
        $normalized = [];

        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }

            $normalized[] = [
                'title' => $item['title'] ?? '',
                'description' => $item['description'] ?? '',
            ];
        }

        return !empty($normalized) ? $normalized : $fallback;
    }

    private function fallbackContent(array $data, string $source = 'fallback'): array
    {
        return $this->normalizeContent([
            'headline' => 'Launch ' . ($data['product_name'] ?? 'Your Product') . ' With Clearer Messaging',
            'subheadline' => 'A focused sales page for ' . ($data['target_audience'] ?? 'your audience') . '.',
            'description' => $data['description'] ?? '',
            'benefits' => [
                [
                    'title' => 'Clearer Product Positioning',
                    'description' => 'Turn raw product information into simple and persuasive sales messaging.',
                ],
                [
                    'title' => 'Faster Landing Page Creation',
                    'description' => 'Create a complete landing page structure without starting from a blank page.',
                ],
                [
                    'title' => 'Audience-Focused Copy',
                    'description' => 'Highlight your product value based on what your target audience actually needs.',
                ],
            ],
            'features' => $this->fallbackFeatures($data),
            'social_proof' => [
                'title' => 'Designed for Practical Product Teams',
                'description' => 'Built to support faster copywriting, product positioning, and landing page creation.',
            ],
            'pricing' => [
                'label' => 'Simple Pricing',
                'price' => $data['price'] ?? '',
                'description' => 'Start with a clear and transparent plan.',
            ],
            'cta' => [
                'text' => 'Ready to turn your product into a polished offer?',
                'button' => 'Create My Page',
            ],
            '_source' => $source,
        ], $data);
    }

    private function fallbackFeatures(array $data): array
    {
        $rawFeatures = $data['features'] ?? '';
        $features = array_filter(array_map('trim', explode(',', $rawFeatures)));

        if (empty($features)) {
            $features = [
                'Product Overview',
                'Benefit Highlights',
                'Clear Call To Action',
            ];
        }

        return array_map(function ($feature) use ($data) {
            return [
                'title' => ucwords($feature),
                'description' => 'Helps users get more value from ' . ($data['product_name'] ?? 'this product') . '.',
            ];
        }, array_slice($features, 0, 5));
    }
}
