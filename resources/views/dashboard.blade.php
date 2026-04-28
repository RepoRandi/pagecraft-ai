<x-app-layout>
    <div class="min-h-screen bg-slate-950 text-white">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

            <div class="mb-8 flex flex-col justify-between gap-6 lg:flex-row lg:items-end">
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-cyan-300">
                        PageCraft AI
                    </p>
                    <h1 class="mt-3 text-3xl font-black md:text-5xl">
                        AI Sales Page Generator
                    </h1>
                    <p class="mt-3 max-w-2xl text-slate-400">
                        Turn product details into a structured, persuasive, and export-ready landing page.
                    </p>
                </div>

                <div class="rounded-3xl border border-cyan-300/20 bg-cyan-300/10 px-5 py-4">
                    <p class="text-xs uppercase tracking-[0.25em] text-cyan-200">Workspace</p>
                    <p class="mt-1 font-bold text-white">{{ Auth::user()->name }}</p>
                </div>
            </div>

            <div class="mb-8 grid gap-4 md:grid-cols-3">
                <div class="rounded-3xl border border-white/10 bg-gradient-to-br from-white/[0.07] to-white/[0.03] p-5">
                    <p class="text-sm text-slate-400">Generated Pages</p>
                    <p class="mt-2 text-3xl font-black">{{ $salesPages->count() }}</p>
                </div>

                <div class="rounded-3xl border border-white/10 bg-gradient-to-br from-white/[0.07] to-white/[0.03] p-5">
                    <p class="text-sm text-slate-400">Latest Page</p>
                    <p class="mt-2 truncate text-lg font-bold">
                        {{ $salesPages->first()?->product_name ?? 'No pages yet' }}
                    </p>
                </div>

                <div class="rounded-3xl border border-white/10 bg-gradient-to-br from-white/[0.07] to-white/[0.03] p-5">
                    <p class="text-sm text-slate-400">Export Support</p>
                    <p class="mt-2 text-lg font-bold text-cyan-200">Standalone HTML</p>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-6 rounded-2xl border border-emerald-400/20 bg-emerald-400/10 px-5 py-4 text-emerald-200">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-red-400/20 bg-red-500/10 px-5 py-4 text-red-200">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="grid gap-8 lg:grid-cols-[1.15fr_0.85fr]">

                <form id="generateForm" method="POST" action="{{ secure_url(route('sales-pages.store', [], false)) }}"
                    class="relative overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-br from-white/[0.07] to-white/[0.03] p-6 shadow-2xl shadow-slate-950/50 backdrop-blur-xl">
                    @csrf

                    <div
                        class="pointer-events-none absolute -right-24 -top-24 h-72 w-72 rounded-full bg-cyan-400/10 blur-3xl">
                    </div>
                    <div
                        class="pointer-events-none absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-blue-500/10 blur-3xl">
                    </div>

                    <div class="relative">
                        <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                            <div>
                                <h2 class="text-xl font-black">Product Details</h2>
                                <p class="mt-1 text-sm text-slate-400">
                                    Provide your product information and let the system generate the page structure.
                                </p>
                            </div>

                            <button type="button" id="sampleBtn"
                                class="rounded-2xl border border-cyan-300/20 bg-cyan-300/10 px-4 py-2 text-sm font-semibold text-cyan-200 transition hover:bg-cyan-300/15">
                                Use Example
                            </button>
                        </div>

                        <div class="grid gap-5">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-300">
                                    Product / Service Name
                                </label>
                                <input id="product_name" name="product_name" value="{{ old('product_name') }}" required
                                    class="w-full rounded-2xl border border-white/10 bg-slate-900/80 px-4 py-3 text-white placeholder:text-slate-500 focus:border-cyan-400 focus:ring-cyan-400"
                                    placeholder="Example: Muslim Habit Tracker App">
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-300">Description</label>
                                <textarea id="description" name="description" rows="4" required
                                    class="w-full rounded-2xl border border-white/10 bg-slate-900/80 px-4 py-3 text-white placeholder:text-slate-500 focus:border-cyan-400 focus:ring-cyan-400"
                                    placeholder="Describe your product or service...">{{ old('description') }}</textarea>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-300">Key Features</label>
                                <textarea id="features" name="features" rows="3" required
                                    class="w-full rounded-2xl border border-white/10 bg-slate-900/80 px-4 py-3 text-white placeholder:text-slate-500 focus:border-cyan-400 focus:ring-cyan-400"
                                    placeholder="Feature one, feature two, feature three">{{ old('features') }}</textarea>
                            </div>

                            <div class="grid gap-5 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-300">Target Audience</label>
                                    <input id="target_audience" name="target_audience"
                                        value="{{ old('target_audience') }}" required
                                        class="w-full rounded-2xl border border-white/10 bg-slate-900/80 px-4 py-3 text-white placeholder:text-slate-500 focus:border-cyan-400 focus:ring-cyan-400"
                                        placeholder="Young professionals">
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-300">Price</label>
                                    <input id="price" name="price" value="{{ old('price') }}" required
                                        class="w-full rounded-2xl border border-white/10 bg-slate-900/80 px-4 py-3 text-white placeholder:text-slate-500 focus:border-cyan-400 focus:ring-cyan-400"
                                        placeholder="RM29/month">
                                </div>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-300">
                                    Unique Selling Points
                                </label>
                                <textarea id="unique_selling_points" name="unique_selling_points" rows="3" required
                                    class="w-full rounded-2xl border border-white/10 bg-slate-900/80 px-4 py-3 text-white placeholder:text-slate-500 focus:border-cyan-400 focus:ring-cyan-400"
                                    placeholder="What makes your offer different?">{{ old('unique_selling_points') }}</textarea>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-300">Design Template</label>
                                <select name="template"
                                    class="w-full rounded-2xl border border-white/10 bg-slate-900/80 px-4 py-3 text-white focus:border-cyan-400 focus:ring-cyan-400">
                                    <option value="dark_luxury">Dark Luxury</option>
                                    <option value="minimal">Minimal Clean</option>
                                    <option value="glassmorphism">Glassmorphism</option>
                                </select>
                            </div>

                            <button type="submit" id="generateBtn"
                                class="rounded-2xl bg-gradient-to-r from-cyan-300 to-blue-500 px-6 py-4 font-black text-slate-950 shadow-lg shadow-cyan-500/20 transition hover:scale-[1.01] hover:brightness-110 disabled:cursor-not-allowed disabled:opacity-70">
                                Generate Sales Page
                            </button>
                        </div>
                    </div>
                </form>

                <div
                    class="rounded-3xl border border-white/10 bg-gradient-to-br from-white/[0.07] to-white/[0.03] p-6 shadow-2xl shadow-slate-950/50 backdrop-blur-xl">
                    <div class="mb-6">
                        <div class="mb-5 flex items-center justify-between gap-4">
                            <div>
                                <h2 class="text-xl font-black">Saved Pages</h2>
                                <p class="mt-1 text-sm text-slate-400">
                                    Search and manage your generated sales pages.
                                </p>
                            </div>
                        </div>

                        <form method="GET" action="{{ secure_url(route('dashboard', [], false)) }}"
                            class="space-y-3">
                            <div class="grid gap-3 xl:grid-cols-[1fr_170px]">
                                <div class="relative">
                                    <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
                                    </svg>

                                    <input name="search" value="{{ request('search') }}"
                                        placeholder="Search product, description, or audience..."
                                        class="w-full rounded-2xl border border-white/10 bg-slate-900/80 px-4 py-3 pl-11 text-sm text-white placeholder:text-slate-500 focus:border-cyan-400 focus:ring-cyan-400">
                                </div>

                                <select name="template"
                                    class="w-full rounded-2xl border border-white/10 bg-slate-900/80 px-4 py-3 text-sm text-white focus:border-cyan-400 focus:ring-cyan-400">
                                    <option value="">All Templates</option>
                                    <option value="dark_luxury" @selected(request('template') === 'dark_luxury')>Dark Luxury</option>
                                    <option value="minimal" @selected(request('template') === 'minimal')>Minimal Clean</option>
                                    <option value="glassmorphism" @selected(request('template') === 'glassmorphism')>Glassmorphism</option>
                                </select>
                            </div>

                            <div class="flex flex-col gap-2 sm:flex-row">
                                <button type="submit"
                                    class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-cyan-300 to-blue-500 px-5 py-3 text-sm font-black text-slate-950 shadow-lg shadow-cyan-500/20 transition hover:scale-[1.01] hover:brightness-110 sm:flex-none">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
                                    </svg>
                                    Search
                                </button>

                                @if (request()->filled('search') || request()->filled('template'))
                                    <a href="{{ secure_url(route('dashboard', [], false)) }}"
                                        class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl border border-white/10 bg-white/[0.04] px-5 py-3 text-sm font-bold text-slate-300 transition hover:bg-white/[0.08] hover:text-white sm:flex-none">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    <div class="space-y-4">
                        @forelse ($salesPages as $page)
                            <div
                                class="rounded-3xl border border-white/10 bg-slate-900/70 p-4 transition hover:border-cyan-300/30">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <h3 class="truncate font-bold">{{ $page->product_name }}</h3>
                                        <p class="mt-1 line-clamp-2 text-sm text-slate-400">
                                            {{ $page->description }}
                                        </p>
                                        <p class="mt-3 text-xs text-slate-500">
                                            {{ $page->created_at->format('d M Y, H:i') }}
                                        </p>
                                    </div>

                                    <div
                                        class="shrink-0 rounded-full bg-cyan-300/10 px-3 py-1 text-xs font-semibold text-cyan-200">
                                        {{ str_replace('_', ' ', $page->template) }}
                                    </div>
                                </div>

                                <div class="mt-4 flex flex-wrap items-center gap-3">
                                    <a href="{{ secure_url(route('sales-pages.show', $page, false)) }}"
                                        class="rounded-2xl bg-white px-4 py-2 text-sm font-bold text-slate-950 transition hover:bg-cyan-100">
                                        Preview
                                    </a>

                                    <a href="{{ secure_url(route('sales-pages.export', $page, false)) }}"
                                        class="rounded-2xl border border-cyan-300/20 bg-cyan-300/10 px-4 py-2 text-sm font-bold text-cyan-200 transition hover:bg-cyan-300/15">
                                        Export
                                    </a>

                                    <form method="POST"
                                        action="{{ secure_url(route('sales-pages.destroy', $page, false)) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            class="rounded-2xl border border-red-400/20 px-4 py-2 text-sm font-bold text-red-300 transition hover:bg-red-500/10">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-3xl border border-dashed border-white/10 p-8 text-center">
                                <div
                                    class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-cyan-300/10 text-cyan-200">
                                    ✨
                                </div>
                                <h3 class="font-bold text-white">No pages found</h3>
                                <p class="mt-2 text-sm text-slate-400">
                                    Try adjusting your search or generate a new sales page.
                                </p>

                                @if (request()->filled('search') || request()->filled('template'))
                                    <a href="{{ secure_url(route('dashboard', [], false)) }}"
                                        class="mt-5 inline-flex rounded-2xl border border-white/10 bg-white/[0.04] px-5 py-3 text-sm font-bold text-slate-300 transition hover:bg-white/[0.08] hover:text-white">
                                        Clear filters
                                    </a>
                                @endif
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div id="loadingOverlay"
            class="fixed inset-0 z-[999] hidden items-center justify-center bg-slate-950/80 px-4 backdrop-blur-sm">
            <div class="w-full max-w-md rounded-3xl border border-white/10 bg-slate-900 p-8 text-center shadow-2xl">
                <div class="mx-auto h-12 w-12 animate-spin rounded-full border-4 border-cyan-300/20 border-t-cyan-300">
                </div>
                <h3 class="mt-6 text-xl font-black">Generating your sales page...</h3>
                <p class="mt-2 text-sm text-slate-400">
                    Creating headline, benefits, features, pricing, and CTA layout.
                </p>
            </div>
        </div>
    </div>

    <script>
        const sampleBtn = document.getElementById('sampleBtn');
        const generateForm = document.getElementById('generateForm');
        const generateBtn = document.getElementById('generateBtn');
        const loadingOverlay = document.getElementById('loadingOverlay');

        sampleBtn?.addEventListener('click', () => {
            document.getElementById('product_name').value = 'Muslim Habit Tracker App';
            document.getElementById('description').value =
                'A mobile app that helps Muslims stay consistent with prayers, habits, Quran notes, and daily spiritual goals.';
            document.getElementById('features').value =
                'Prayer tracker, habit reminder, Quran notes, daily streak, personal progress dashboard';
            document.getElementById('target_audience').value = 'Young Muslim professionals';
            document.getElementById('price').value = 'RM29/month';
            document.getElementById('unique_selling_points').value =
                'Designed for Muslim lifestyle, minimalist interface, productivity and spiritual balance in one app.';
        });

        generateForm?.addEventListener('submit', () => {
            generateBtn.disabled = true;
            generateBtn.textContent = 'Generating...';

            loadingOverlay.classList.remove('hidden');
            loadingOverlay.classList.add('flex');
        });
    </script>
</x-app-layout>
