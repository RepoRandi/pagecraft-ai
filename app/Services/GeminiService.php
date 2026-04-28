<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GeminiService
{
    public function generateSalesPage(array $data): array
    {
        $apiKey = config('services.gemini.api_key');
        $model = config('services.gemini.model', 'gemini-2.5-flash');
        $maxTokens = (int) config('services.gemini.max_output_tokens', 4096);
        $timeout = (int) config('services.gemini.timeout', 45);
        $debug = filter_var(env('GEMINI_DEBUG', false), FILTER_VALIDATE_BOOLEAN);

        if (!$apiKey) {
            if ($debug) {
                dd([
                    'source' => 'fallback_local',
                    'reason' => 'GEMINI_API_KEY is empty',
                ]);
            }

            return $this->withSource($this->fallbackSalesPage($data), 'fallback_local');
        }

        $cacheKey = 'sales_page_ai_' . md5(json_encode([
            'product_name' => $data['product_name'] ?? '',
            'description' => $data['description'] ?? '',
            'features' => $data['features'] ?? '',
            'target_audience' => $data['target_audience'] ?? '',
            'price' => $data['price'] ?? '',
            'unique_selling_points' => $data['unique_selling_points'] ?? '',
            'model' => $model,
            'prompt_version' => 'v2_compact_json',
        ]));

        return Cache::remember($cacheKey, now()->addDay(), function () use (
            $apiKey,
            $model,
            $maxTokens,
            $timeout,
            $data,
            $debug
        ) {
            try {
                $prompt = $this->buildCompactPrompt($data);

                $response = Http::timeout($timeout)
                    ->retry(2, 1200)
                    ->post(
                        "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}",
                        [
                            'contents' => [
                                [
                                    'parts' => [
                                        ['text' => $prompt],
                                    ],
                                ],
                            ],
                            'generationConfig' => [
                                'temperature' => 0.65,
                                'topP' => 0.9,
                                'maxOutputTokens' => $maxTokens,
                                'responseMimeType' => 'application/json',
                            ],
                        ]
                    );

                if ($response->failed()) {
                    if ($debug) {
                        dd([
                            'source' => 'gemini_failed',
                            'status' => $response->status(),
                            'body' => $response->json(),
                            'raw_body' => $response->body(),
                            'model' => $model,
                        ]);
                    }

                    Log::warning('Gemini API failed. Using fallback.', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                        'model' => $model,
                    ]);

                    return $this->withSource($this->fallbackSalesPage($data), 'fallback_local');
                }

                $text = $response->json('candidates.0.content.parts.0.text');

                if (!$text) {
                    if ($debug) {
                        dd([
                            'source' => 'gemini_empty_response',
                            'response' => $response->json(),
                            'model' => $model,
                        ]);
                    }

                    return $this->withSource($this->fallbackSalesPage($data), 'fallback_local');
                }

                $result = $this->parseJsonResponse($text, $data);
                $result = $this->withSource($result, 'gemini_ai');

                if ($debug) {
                    dd([
                        'source' => 'gemini_ai',
                        'model' => $model,
                        'raw_text' => $text,
                        'result' => $result,
                    ]);
                }

                return $result;
            } catch (\Throwable $e) {
                if ($debug) {
                    dd([
                        'source' => 'gemini_exception',
                        'message' => $e->getMessage(),
                        'model' => $model,
                    ]);
                }

                Log::warning('Gemini exception. Using fallback.', [
                    'message' => $e->getMessage(),
                    'model' => $model,
                ]);

                return $this->withSource($this->fallbackSalesPage($data), 'fallback_local');
            }
        });
    }

    private function buildCompactPrompt(array $data): string
    {
        return <<<PROMPT
Generate a concise high-converting sales page as valid JSON only.

Product: {$data['product_name']}
Description: {$data['description']}
Features: {$data['features']}
Audience: {$data['target_audience']}
Price: {$data['price']}
USP: {$data['unique_selling_points']}

Rules:
- Return JSON only.
- Do not use markdown.
- Keep every string concise, maximum 18 words.
- Return exactly 3 benefits.
- Return maximum 4 features.
- Make copy specific to the product and audience.
- Avoid generic words like "growth", "better results", or "complexity" unless necessary.
- CTA must be short and action-oriented.

Required JSON schema:
{
  "headline": "string",
  "subheadline": "string",
  "description": "string",
  "benefits": [
    {"title": "string", "description": "string"},
    {"title": "string", "description": "string"},
    {"title": "string", "description": "string"}
  ],
  "features": [
    {"title": "string", "description": "string"}
  ],
  "social_proof": {
    "title": "string",
    "description": "string"
  },
  "pricing": {
    "label": "string",
    "price": "string",
    "description": "string"
  },
  "cta": {
    "text": "string",
    "button": "string"
  }
}
PROMPT;
    }

    private function parseJsonResponse(string $text, array $data): array
    {
        $cleanText = trim($text);
        $cleanText = preg_replace('/^```json\s*/', '', $cleanText);
        $cleanText = preg_replace('/^```\s*/', '', $cleanText);
        $cleanText = preg_replace('/\s*```$/', '', $cleanText);

        $json = json_decode($cleanText, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($json)) {
            Log::warning('Invalid Gemini JSON. Using fallback.', [
                'raw' => $text,
                'error' => json_last_error_msg(),
            ]);

            return $this->fallbackSalesPage($data);
        }

        return $this->normalizeSalesPage($json, $data);
    }

    private function normalizeSalesPage(array $json, array $data): array
    {
        $fallback = $this->fallbackSalesPage($data);

        $features = $json['features'] ?? $fallback['features'];
        $benefits = $json['benefits'] ?? $fallback['benefits'];

        return [
            'headline' => $this->safeString($json['headline'] ?? $fallback['headline']),
            'subheadline' => $this->safeString($json['subheadline'] ?? $fallback['subheadline']),
            'description' => $this->safeString($json['description'] ?? $fallback['description']),
            'benefits' => collect($benefits)
                ->take(3)
                ->map(fn($item) => [
                    'title' => $this->safeString($item['title'] ?? 'Key Benefit'),
                    'description' => $this->safeString($item['description'] ?? 'A clear benefit for the target audience.'),
                ])
                ->values()
                ->toArray(),
            'features' => collect($features)
                ->take(4)
                ->map(fn($item) => [
                    'title' => $this->safeString($item['title'] ?? 'Feature'),
                    'description' => $this->safeString($item['description'] ?? 'A useful feature for the product.'),
                ])
                ->values()
                ->toArray(),
            'social_proof' => [
                'title' => $this->safeString($json['social_proof']['title'] ?? $fallback['social_proof']['title']),
                'description' => $this->safeString($json['social_proof']['description'] ?? $fallback['social_proof']['description']),
            ],
            'pricing' => [
                'label' => $this->safeString($json['pricing']['label'] ?? 'Simple Pricing'),
                'price' => $this->safeString($json['pricing']['price'] ?? $data['price']),
                'description' => $this->safeString($json['pricing']['description'] ?? 'Start today with a clear plan.'),
            ],
            'cta' => [
                'text' => $this->safeString($json['cta']['text'] ?? 'Ready to get started?'),
                'button' => $this->safeString($json['cta']['button'] ?? 'Get Started'),
            ],
        ];
    }

    private function fallbackSalesPage(array $data): array
    {
        return [
            'headline' => 'Launch ' . $data['product_name'] . ' With Clearer Messaging',
            'subheadline' => 'A focused sales page for ' . $data['target_audience'] . '.',
            'description' => $data['description'],
            'benefits' => [
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
            ],
            'features' => collect(explode(',', $data['features']))
                ->filter()
                ->take(4)
                ->map(fn($feature) => [
                    'title' => Str::headline(trim($feature)),
                    'description' => 'Helps users get more value from ' . $data['product_name'] . '.',
                ])
                ->values()
                ->toArray(),
            'social_proof' => [
                'title' => 'Designed for Practical Product Teams',
                'description' => 'Built to support faster copywriting and landing page creation.',
            ],
            'pricing' => [
                'label' => 'Simple Pricing',
                'price' => $data['price'],
                'description' => 'Start with a clear and transparent plan.',
            ],
            'cta' => [
                'text' => 'Ready to turn your product into a polished offer?',
                'button' => 'Create My Page',
            ],
        ];
    }

    private function safeString(mixed $value): string
    {
        if (!is_string($value)) {
            return '';
        }

        return trim($value);
    }

    private function withSource(array $data, string $source): array
    {
        $data['_source'] = $source;

        return $data;
    }
}
