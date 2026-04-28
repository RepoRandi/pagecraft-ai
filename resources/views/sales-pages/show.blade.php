<x-app-layout>
    @php
        $content = $salesPage->generated_content;

        $score = 0;

        if (strlen($content['headline'] ?? '') > 20) {
            $score += 20;
        }

        if (!empty($content['benefits'])) {
            $score += 20;
        }

        if (!empty($content['features'])) {
            $score += 20;
        }

        if (!empty($content['cta']['button'] ?? null)) {
            $score += 20;
        }

        if (!empty($content['pricing']['price'] ?? null)) {
            $score += 20;
        }

        $scoreLabel = $score >= 80 ? 'Strong' : ($score >= 60 ? 'Good' : 'Needs Improvement');

        $fullCopy = trim(
            ($content['headline'] ?? '') .
                "\n\n" .
                ($content['subheadline'] ?? '') .
                "\n\n" .
                ($content['description'] ?? '') .
                "\n\n" .
                'CTA: ' .
                ($content['cta']['button'] ?? ''),
        );

        $template = $salesPage->template ?? 'dark_luxury';

        $theme = match ($template) {
            'minimal' => [
                'page' => 'bg-white text-slate-950',
                'panel' => 'border border-slate-200 bg-white shadow-xl',
                'hero' => 'bg-gradient-to-br from-white via-slate-50 to-cyan-50',
                'label' => 'text-blue-600',
                'muted' => 'text-slate-600',
                'card' => 'border border-slate-200 bg-slate-50',
                'feature' => 'border border-slate-200 bg-white',
                'cta' => 'bg-slate-950 text-white hover:bg-slate-800',
                'secondaryCta' => 'bg-blue-600 text-white hover:bg-blue-500',
                'social' => 'border border-blue-200 bg-blue-50',
                'pricing' => 'border-t border-slate-200',
            ],
            'glassmorphism' => [
                'page' => 'bg-gradient-to-br from-slate-950 via-cyan-950 to-blue-950 text-white',
                'panel' => 'border border-white/10 bg-white/[0.06] shadow-2xl backdrop-blur-xl',
                'hero' => 'bg-white/[0.05]',
                'label' => 'text-cyan-200',
                'muted' => 'text-slate-300',
                'card' => 'border border-white/10 bg-white/[0.08] backdrop-blur-xl',
                'feature' => 'border border-white/10 bg-white/[0.06] backdrop-blur-xl',
                'cta' => 'bg-white text-slate-950 hover:bg-cyan-100',
                'secondaryCta' => 'bg-gradient-to-r from-cyan-300 to-blue-500 text-slate-950',
                'social' => 'border border-cyan-300/20 bg-cyan-300/10 backdrop-blur-xl',
                'pricing' => 'border-t border-white/10',
            ],
            default => [
                'page' => 'bg-slate-950 text-white',
                'panel' =>
                    'border border-white/10 bg-gradient-to-br from-slate-900 via-slate-950 to-cyan-950 shadow-2xl',
                'hero' => 'bg-transparent',
                'label' => 'text-cyan-300',
                'muted' => 'text-slate-300',
                'card' => 'border border-white/10 bg-white/[0.06] backdrop-blur',
                'feature' => 'border border-white/10 bg-slate-900/80',
                'cta' => 'bg-white text-slate-950 hover:bg-cyan-100',
                'secondaryCta' => 'bg-gradient-to-r from-cyan-300 to-blue-500 text-slate-950',
                'social' => 'border border-cyan-300/20 bg-cyan-300/10',
                'pricing' => 'border-t border-white/10',
            ],
        };
    @endphp

    <div class="min-h-screen bg-slate-950 text-white">
        <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">

            <div class="mb-8 flex flex-col justify-between gap-5 lg:flex-row lg:items-end">
                <div>
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center rounded-2xl border border-white/10 bg-white/[0.04] px-4 py-2 text-sm font-semibold text-slate-300 transition hover:border-cyan-300/30 hover:bg-white/[0.07] hover:text-white">
                        ← Back to dashboard
                    </a>

                    <p class="mt-6 text-sm uppercase tracking-[0.35em] text-cyan-300">Live Preview</p>

                    <h1 class="mt-3 text-3xl font-black md:text-5xl">
                        {{ $salesPage->product_name }}
                    </h1>

                    <p class="mt-3 max-w-2xl text-slate-400">
                        Preview generated from your product input, rendered with selected design template.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <button type="button" onclick='copyText(@json($content['headline'] ?? ''))'
                        class="rounded-2xl border border-white/10 bg-white/[0.04] px-5 py-3 text-sm font-bold text-slate-300 transition hover:bg-white/[0.08] hover:text-white">
                        Copy Headline
                    </button>

                    <button type="button" onclick='copyText(@json($content['cta']['button'] ?? ''))'
                        class="rounded-2xl border border-white/10 bg-white/[0.04] px-5 py-3 text-sm font-bold text-slate-300 transition hover:bg-white/[0.08] hover:text-white">
                        Copy CTA
                    </button>

                    <button type="button" onclick='copyText(@json($fullCopy))'
                        class="rounded-2xl border border-cyan-300/20 bg-cyan-300/10 px-5 py-3 text-sm font-bold text-cyan-200 transition hover:bg-cyan-300/15">
                        Copy Full Copy
                    </button>

                    <form method="POST" action="{{ route('sales-pages.regenerate-section', $salesPage) }}">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="section" value="headline">

                        <button type="submit"
                            class="rounded-2xl border border-cyan-300/20 bg-cyan-300/10 px-5 py-3 text-sm font-bold text-cyan-200 transition hover:bg-cyan-300/15">
                            Regenerate Headline
                        </button>
                    </form>

                    <form method="POST" action="{{ route('sales-pages.regenerate-section', $salesPage) }}">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="section" value="cta">

                        <button type="submit"
                            class="rounded-2xl border border-cyan-300/20 bg-cyan-300/10 px-5 py-3 text-sm font-bold text-cyan-200 transition hover:bg-cyan-300/15">
                            Regenerate CTA
                        </button>
                    </form>

                    <form method="POST" action="{{ route('sales-pages.regenerate-section', $salesPage) }}">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="section" value="benefits">

                        <button type="submit"
                            class="rounded-2xl border border-cyan-300/20 bg-cyan-300/10 px-5 py-3 text-sm font-bold text-cyan-200 transition hover:bg-cyan-300/15">
                            Regenerate Benefits
                        </button>
                    </form>

                    <a href="{{ route('sales-pages.export', $salesPage) }}"
                        class="rounded-2xl bg-gradient-to-r from-cyan-300 to-blue-500 px-5 py-3 text-sm font-black text-slate-950 shadow-lg shadow-cyan-500/20 transition hover:scale-[1.01] hover:brightness-110">
                        Export HTML
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-6 rounded-2xl border border-emerald-400/20 bg-emerald-400/10 px-5 py-4 text-emerald-200">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6 grid gap-4 md:grid-cols-4">
                <div class="rounded-3xl border border-cyan-300/20 bg-cyan-300/10 p-5">
                    <p class="text-xs uppercase tracking-[0.25em] text-cyan-200">Conversion Score</p>
                    <p class="mt-3 text-4xl font-black text-white">{{ $score }}/100</p>
                    <p class="mt-1 text-sm font-semibold text-cyan-200">{{ $scoreLabel }}</p>
                </div>

                <div class="rounded-3xl border border-white/10 bg-gradient-to-br from-white/[0.07] to-white/[0.03] p-5">
                    <p class="text-sm text-slate-400">Audience Fit</p>
                    <p class="mt-2 text-lg font-bold text-white">{{ $salesPage->target_audience }}</p>
                </div>

                <div class="rounded-3xl border border-white/10 bg-gradient-to-br from-white/[0.07] to-white/[0.03] p-5">
                    <p class="text-sm text-slate-400">Template</p>
                    <p class="mt-2 text-lg font-bold text-white capitalize">
                        {{ str_replace('_', ' ', $salesPage->template) }}
                    </p>
                </div>

                <div class="rounded-3xl border border-white/10 bg-gradient-to-br from-white/[0.07] to-white/[0.03] p-5">
                    <p class="text-sm text-slate-400">Export Format</p>
                    <p class="mt-2 text-lg font-bold text-cyan-200">Standalone HTML</p>
                </div>
            </div>

            <div class="overflow-hidden rounded-[2rem] {{ $theme['panel'] }} {{ $theme['page'] }}">
                <section class="relative overflow-hidden px-8 py-16 text-center md:px-16 {{ $theme['hero'] }}">
                    @if ($template !== 'minimal')
                        <div
                            class="pointer-events-none absolute left-1/2 top-0 h-72 w-72 -translate-x-1/2 rounded-full bg-cyan-400/10 blur-3xl">
                        </div>
                    @endif

                    <div class="relative">
                        <p class="mb-4 text-sm uppercase tracking-[0.35em] {{ $theme['label'] }}">
                            {{ $salesPage->target_audience }}
                        </p>

                        <h2 class="mx-auto max-w-4xl text-4xl font-black leading-tight md:text-6xl">
                            {{ $content['headline'] ?? 'Generated Headline' }}
                        </h2>

                        <p class="mx-auto mt-6 max-w-2xl text-lg leading-8 {{ $theme['muted'] }}">
                            {{ $content['subheadline'] ?? '' }}
                        </p>

                        <div class="mt-8">
                            <a href="#pricing"
                                class="inline-flex rounded-2xl px-8 py-4 font-black shadow-lg transition hover:scale-[1.02] {{ $theme['secondaryCta'] }}">
                                {{ $content['cta']['button'] ?? 'Get Started' }}
                            </a>
                        </div>
                    </div>
                </section>

                <section class="border-t border-current/10 px-8 py-12 md:px-16">
                    <h3 class="text-2xl font-black">About this offer</h3>
                    <p class="mt-4 text-lg leading-8 {{ $theme['muted'] }}">
                        {{ $content['description'] ?? '' }}
                    </p>
                </section>

                <section class="grid gap-5 border-t border-current/10 px-8 py-12 md:grid-cols-3 md:px-16">
                    @forelse (($content['benefits'] ?? []) as $benefit)
                        <div class="rounded-3xl p-6 transition hover:scale-[1.01] {{ $theme['card'] }}">
                            <h4 class="text-lg font-bold {{ $theme['label'] }}">
                                {{ $benefit['title'] ?? '' }}
                            </h4>
                            <p class="mt-3 leading-7 {{ $theme['muted'] }}">
                                {{ $benefit['description'] ?? '' }}
                            </p>
                        </div>
                    @empty
                        <div class="rounded-3xl p-6 {{ $theme['card'] }}">No benefits generated.</div>
                    @endforelse
                </section>

                <section class="border-t border-current/10 px-8 py-12 md:px-16">
                    <h3 class="text-2xl font-black">Features Breakdown</h3>

                    <div class="mt-6 grid gap-4 md:grid-cols-2">
                        @forelse (($content['features'] ?? []) as $feature)
                            <div class="rounded-2xl p-5 transition hover:scale-[1.01] {{ $theme['feature'] }}">
                                <h4 class="font-bold">
                                    {{ ucwords($feature['title'] ?? '') }}
                                </h4>
                                <p class="mt-2 leading-7 {{ $theme['muted'] }}">
                                    {{ $feature['description'] ?? '' }}
                                </p>
                            </div>
                        @empty
                            <div class="rounded-2xl p-5 {{ $theme['feature'] }}">No features generated.</div>
                        @endforelse
                    </div>
                </section>

                <section class="border-t border-current/10 px-8 py-12 md:px-16">
                    <div class="rounded-3xl p-8 {{ $theme['social'] }}">
                        <p class="text-xs uppercase tracking-[0.25em] {{ $theme['label'] }}">Social Proof</p>
                        <h3 class="mt-3 text-2xl font-black">
                            {{ $content['social_proof']['title'] ?? 'Trusted by users' }}
                        </h3>
                        <p class="mt-3 leading-7 {{ $theme['muted'] }}">
                            {{ $content['social_proof']['description'] ?? '' }}
                        </p>
                    </div>
                </section>

                <section id="pricing" class="{{ $theme['pricing'] }} px-8 py-16 text-center md:px-16">
                    <p class="text-sm font-semibold uppercase tracking-[0.25em] {{ $theme['label'] }}">
                        {{ $content['pricing']['label'] ?? 'Pricing' }}
                    </p>

                    <h3 class="mt-4 text-5xl font-black">
                        {{ $content['pricing']['price'] ?? $salesPage->price }}
                    </h3>

                    <p class="mx-auto mt-4 max-w-xl leading-7 {{ $theme['muted'] }}">
                        {{ $content['pricing']['description'] ?? '' }}
                    </p>

                    <p class="mx-auto mt-8 max-w-xl text-xl font-bold">
                        {{ $content['cta']['text'] ?? '' }}
                    </p>

                    <a href="#"
                        class="mt-6 inline-flex rounded-2xl px-8 py-4 font-black transition {{ $theme['cta'] }}">
                        {{ $content['cta']['button'] ?? 'Buy Now' }}
                    </a>
                </section>
            </div>

            <div id="copyToast"
                class="fixed bottom-6 left-1/2 z-[999] hidden -translate-x-1/2 rounded-2xl border border-emerald-400/20 bg-emerald-400/10 px-5 py-3 text-sm font-semibold text-emerald-200 shadow-2xl backdrop-blur">
                Copied to clipboard
            </div>
        </div>
    </div>

    <script>
        function copyText(text) {
            navigator.clipboard.writeText(text || '').then(() => {
                const toast = document.getElementById('copyToast');
                toast.classList.remove('hidden');

                setTimeout(() => {
                    toast.classList.add('hidden');
                }, 1600);
            });
        }
    </script>
</x-app-layout>
