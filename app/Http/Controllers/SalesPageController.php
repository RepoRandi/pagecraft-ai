<?php

namespace App\Http\Controllers;

use App\Models\SalesPage;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesPageController extends Controller
{
    public function index(Request $request)
    {
        $query = SalesPage::where('user_id', Auth::id())
            ->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('product_name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%')
                    ->orWhere('target_audience', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('template')) {
            $query->where('template', $request->template);
        }

        $salesPages = $query->get();

        return view('dashboard', compact('salesPages'));
    }

    public function store(Request $request, GeminiService $geminiService)
    {
        $validated = $request->validate([
            'product_name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'features' => ['required', 'string'],
            'target_audience' => ['required', 'string', 'max:255'],
            'price' => ['required', 'string', 'max:255'],
            'unique_selling_points' => ['required', 'string'],
            'template' => ['required', 'in:dark_luxury,minimal,glassmorphism'],
        ]);

        try {
            $generatedContent = $geminiService->generateSalesPage($validated);

            $salesPage = SalesPage::create([
                ...$validated,
                'user_id' => Auth::id(),
                'generated_content' => $generatedContent,
            ]);

            return redirect()
                ->route('sales-pages.show', $salesPage)
                ->with('success', 'Sales page generated successfully.');
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'generate' => $e->getMessage(),
                ]);
        }
    }

    public function show(SalesPage $salesPage)
    {
        abort_if($salesPage->user_id !== Auth::id(), 403);

        $scoreData = $this->generateScore($salesPage->generated_content);

        return view('sales-pages.show', compact('salesPage', 'scoreData'));
    }

    public function destroy(SalesPage $salesPage)
    {
        abort_if($salesPage->user_id !== Auth::id(), 403);

        $salesPage->delete();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Sales page deleted successfully.');
    }

    public function exportHtml(SalesPage $salesPage)
    {
        abort_if($salesPage->user_id !== Auth::id(), 403);

        $html = view('sales-pages.export', compact('salesPage'))->render();

        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="' . str($salesPage->product_name)->slug() . '-sales-page.html"');
    }

    private function generateScore(array $data): array
    {
        $score = 0;

        // Simple heuristic scoring
        if (strlen($data['headline'] ?? '') > 20) $score += 20;
        if (!empty($data['benefits'])) $score += 20;
        if (!empty($data['features'])) $score += 20;
        if (!empty($data['cta']['button'] ?? null)) $score += 20;
        if (!empty($data['pricing']['price'] ?? null)) $score += 20;

        return [
            'score' => $score,
            'label' => $score >= 80 ? 'Strong' : ($score >= 60 ? 'Good' : 'Needs Improvement'),
        ];
    }

    public function regenerateSection(Request $request, SalesPage $salesPage)
    {
        abort_if($salesPage->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'section' => ['required', 'in:headline,cta,benefits'],
        ]);

        $content = $salesPage->generated_content;

        if ($validated['section'] === 'headline') {
            $content['headline'] = 'Unlock Better Results with ' . $salesPage->product_name;
            $content['subheadline'] = 'A focused solution for ' . $salesPage->target_audience . ' who want clarity, consistency, and measurable growth.';
        }

        if ($validated['section'] === 'cta') {
            $content['cta'] = [
                'text' => 'Start building better outcomes with a solution designed around your needs.',
                'button' => 'Start Now',
            ];
        }

        if ($validated['section'] === 'benefits') {
            $content['benefits'] = [
                [
                    'title' => 'Move Faster',
                    'description' => 'Reduce manual effort and get a structured page ready in less time.',
                ],
                [
                    'title' => 'Communicate Clearly',
                    'description' => 'Turn product details into persuasive messaging your audience can understand.',
                ],
                [
                    'title' => 'Increase Confidence',
                    'description' => 'Present your offer with a polished layout and conversion-focused structure.',
                ],
            ];
        }

        $salesPage->update([
            'generated_content' => $content,
        ]);

        return redirect()
            ->route('sales-pages.show', $salesPage)
            ->with('success', ucfirst($validated['section']) . ' regenerated successfully.');
    }
}
