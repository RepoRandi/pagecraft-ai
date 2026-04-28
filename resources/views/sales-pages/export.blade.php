@php
    $content = $salesPage->generated_content;
    $template = $salesPage->template ?? 'dark_luxury';

    $theme = match ($template) {
        'minimal' => [
            'body' => 'bg-white text-slate-950',
            'panel' => 'border border-slate-200 bg-white shadow-xl',
            'hero' => 'bg-gradient-to-br from-white via-slate-50 to-cyan-50',
            'label' => 'text-blue-600',
            'muted' => 'text-slate-600',
            'card' => 'border border-slate-200 bg-slate-50',
            'feature' => 'border border-slate-200 bg-white',
            'primaryButton' => 'bg-blue-600 text-white',
            'secondaryButton' => 'bg-slate-950 text-white',
            'social' => 'border border-blue-200 bg-blue-50',
            'divider' => 'border-slate-200',
        ],
        'glassmorphism' => [
            'body' => 'bg-gradient-to-br from-slate-950 via-cyan-950 to-blue-950 text-white',
            'panel' => 'border border-white/10 bg-white/[0.06] shadow-2xl backdrop-blur-xl',
            'hero' => 'bg-white/[0.05]',
            'label' => 'text-cyan-200',
            'muted' => 'text-slate-300',
            'card' => 'border border-white/10 bg-white/[0.08] backdrop-blur-xl',
            'feature' => 'border border-white/10 bg-white/[0.06] backdrop-blur-xl',
            'primaryButton' => 'bg-gradient-to-r from-cyan-300 to-blue-500 text-slate-950',
            'secondaryButton' => 'bg-white text-slate-950',
            'social' => 'border border-cyan-300/20 bg-cyan-300/10 backdrop-blur-xl',
            'divider' => 'border-white/10',
        ],
        default => [
            'body' => 'bg-slate-950 text-white',
            'panel' => 'border border-white/10 bg-gradient-to-br from-slate-900 via-slate-950 to-cyan-950 shadow-2xl',
            'hero' => 'bg-transparent',
            'label' => 'text-cyan-300',
            'muted' => 'text-slate-300',
            'card' => 'border border-white/10 bg-white/[0.06] backdrop-blur',
            'feature' => 'border border-white/10 bg-slate-900/80',
            'primaryButton' => 'bg-gradient-to-r from-cyan-300 to-blue-500 text-slate-950',
            'secondaryButton' => 'bg-white text-slate-950',
            'social' => 'border border-cyan-300/20 bg-cyan-300/10',
            'divider' => 'border-white/10',
        ],
    };
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $salesPage->product_name }} | AI Generated Sales Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="{{ $theme['body'] }}">
    <main class="min-h-screen px-4 py-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-6xl overflow-hidden rounded-[2rem] {{ $theme['panel'] }}">

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

                    <h1 class="mx-auto max-w-4xl text-4xl font-black leading-tight md:text-6xl">
                        {{ $content['headline'] ?? 'Generated Headline' }}
                    </h1>

                    <p class="mx-auto mt-6 max-w-2xl text-lg leading-8 {{ $theme['muted'] }}">
                        {{ $content['subheadline'] ?? '' }}
                    </p>

                    <div class="mt-8">
                        <a href="#pricing"
                            class="inline-flex rounded-2xl px-8 py-4 font-black shadow-lg {{ $theme['primaryButton'] }}">
                            {{ $content['cta']['button'] ?? 'Get Started' }}
                        </a>
                    </div>
                </div>
            </section>

            <section class="border-t {{ $theme['divider'] }} px-8 py-12 md:px-16">
                <h2 class="text-2xl font-black">About this offer</h2>
                <p class="mt-4 text-lg leading-8 {{ $theme['muted'] }}">
                    {{ $content['description'] ?? '' }}
                </p>
            </section>

            <section class="grid gap-5 border-t {{ $theme['divider'] }} px-8 py-12 md:grid-cols-3 md:px-16">
                @foreach ($content['benefits'] ?? [] as $benefit)
                    <div class="rounded-3xl p-6 {{ $theme['card'] }}">
                        <h3 class="text-lg font-bold {{ $theme['label'] }}">
                            {{ $benefit['title'] ?? '' }}
                        </h3>
                        <p class="mt-3 leading-7 {{ $theme['muted'] }}">
                            {{ $benefit['description'] ?? '' }}
                        </p>
                    </div>
                @endforeach
            </section>

            <section class="border-t {{ $theme['divider'] }} px-8 py-12 md:px-16">
                <h2 class="text-2xl font-black">Features Breakdown</h2>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    @foreach ($content['features'] ?? [] as $feature)
                        <div class="rounded-2xl p-5 {{ $theme['feature'] }}">
                            <h3 class="font-bold">
                                {{ ucwords($feature['title'] ?? '') }}
                            </h3>
                            <p class="mt-2 leading-7 {{ $theme['muted'] }}">
                                {{ $feature['description'] ?? '' }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="border-t {{ $theme['divider'] }} px-8 py-12 md:px-16">
                <div class="rounded-3xl p-8 {{ $theme['social'] }}">
                    <p class="text-xs uppercase tracking-[0.25em] {{ $theme['label'] }}">
                        Social Proof
                    </p>
                    <h2 class="mt-3 text-2xl font-black">
                        {{ $content['social_proof']['title'] ?? 'Trusted by users' }}
                    </h2>
                    <p class="mt-3 leading-7 {{ $theme['muted'] }}">
                        {{ $content['social_proof']['description'] ?? '' }}
                    </p>
                </div>
            </section>

            <section id="pricing" class="border-t {{ $theme['divider'] }} px-8 py-16 text-center md:px-16">
                <p class="text-sm font-semibold uppercase tracking-[0.25em] {{ $theme['label'] }}">
                    {{ $content['pricing']['label'] ?? 'Pricing' }}
                </p>

                <h2 class="mt-4 text-5xl font-black">
                    {{ $content['pricing']['price'] ?? $salesPage->price }}
                </h2>

                <p class="mx-auto mt-4 max-w-xl leading-7 {{ $theme['muted'] }}">
                    {{ $content['pricing']['description'] ?? '' }}
                </p>

                <p class="mx-auto mt-8 max-w-xl text-xl font-bold">
                    {{ $content['cta']['text'] ?? '' }}
                </p>

                <a href="#"
                    class="mt-6 inline-flex rounded-2xl px-8 py-4 font-black {{ $theme['secondaryButton'] }}">
                    {{ $content['cta']['button'] ?? 'Buy Now' }}
                </a>
            </section>

        </div>
    </main>
</body>

</html>
