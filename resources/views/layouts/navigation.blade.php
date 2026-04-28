<nav x-data="{ open: false, dropdown: false }"
    class="sticky top-0 z-50 border-b border-white/10 bg-slate-950/90 text-white backdrop-blur-xl">

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">

            <!-- Brand -->
            <a href="{{ secure_url(route('dashboard', [], false)) }}" class="group flex items-center gap-3">
                <div
                    class="flex h-10 w-10 items-center justify-center rounded-2xl border border-cyan-300/30 bg-gradient-to-br from-cyan-300/20 via-slate-900 to-blue-500/20 shadow-lg shadow-cyan-500/10 transition group-hover:shadow-cyan-500/20">
                    <span class="text-sm font-black text-cyan-200">PC</span>
                </div>

                <div class="leading-tight">
                    <p class="text-base font-black tracking-tight">
                        PageCraft AI
                    </p>
                    <p class="hidden text-xs text-slate-400 sm:block">
                        AI Sales Page Generator
                    </p>
                </div>
            </a>

            <!-- Desktop Nav -->
            <div class="hidden items-center gap-3 md:flex">
                <a href="{{ secure_url(route('dashboard', [], false)) }}"
                    class="rounded-2xl border border-cyan-300/20 bg-cyan-300/10 px-4 py-2 text-sm font-semibold text-cyan-200 shadow-sm transition hover:bg-cyan-300/15">
                    Dashboard
                </a>

                <a href="{{ secure_url(route('profile.edit', [], false)) }}"
                    class="rounded-2xl px-4 py-2 text-sm font-medium text-slate-300 transition hover:bg-white/5 hover:text-white">
                    Profile
                </a>
            </div>

            <!-- User Menu -->
            <div class="flex items-center gap-3">
                <div class="relative hidden sm:block">
                    <button type="button" @click="dropdown = !dropdown" @click.outside="dropdown = false"
                        class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/[0.04] px-3 py-2 text-sm transition hover:border-cyan-300/30 hover:bg-white/[0.07]">

                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-cyan-300 to-blue-500 text-xs font-black text-slate-950">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>

                        <div class="hidden text-left lg:block">
                            <p class="max-w-32 truncate text-sm font-semibold text-white">
                                {{ Auth::user()->name }}
                            </p>
                            <p class="max-w-36 truncate text-xs text-slate-400">
                                {{ Auth::user()->email }}
                            </p>
                        </div>

                        <svg class="h-4 w-4 text-slate-400 transition" :class="{ 'rotate-180': dropdown }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="dropdown" x-transition x-cloak
                        class="absolute right-0 mt-3 w-64 overflow-hidden rounded-3xl border border-white/10 bg-slate-900/95 shadow-2xl backdrop-blur-xl">

                        <div class="border-b border-white/10 p-4">
                            <p class="truncate text-sm font-bold text-white">
                                {{ Auth::user()->name }}
                            </p>
                            <p class="mt-1 truncate text-xs text-slate-400">
                                {{ Auth::user()->email }}
                            </p>
                        </div>

                        <div class="p-2">
                            <a href="{{ secure_url(route('profile.edit', [], false)) }}"
                                class="block rounded-2xl px-4 py-3 text-sm text-slate-300 transition hover:bg-white/5 hover:text-white">
                                Profile Settings
                            </a>

                            <form method="POST" action="{{ secure_url(route('logout', [], false)) }}"
                                onsubmit="showLogoutLoading()">
                                @csrf
                                <button type="submit"
                                    class="w-full rounded-2xl px-4 py-3 text-left text-sm text-red-300 transition hover:bg-red-500/10 hover:text-red-200">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Mobile Toggle -->
                <button type="button" @click="open = !open"
                    class="inline-flex rounded-2xl border border-white/10 bg-white/[0.04] p-2 text-slate-300 transition hover:bg-white/[0.07] hover:text-white md:hidden">
                    <svg x-show="!open" class="h-6 w-6">
                        <path stroke="currentColor" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="open" class="h-6 w-6">
                        <path stroke="currentColor" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" x-transition x-cloak class="border-t border-white/10 bg-slate-950/95 md:hidden">
        <div class="space-y-2 px-4 py-4">
            <a href="{{ secure_url(route('dashboard', [], false)) }}"
                class="block rounded-2xl border border-cyan-300/20 bg-cyan-300/10 px-4 py-3 text-sm font-semibold text-cyan-200">
                Dashboard
            </a>

            <a href="{{ secure_url(route('profile.edit', [], false)) }}"
                class="block rounded-2xl px-4 py-3 text-sm text-slate-300 hover:bg-white/5 hover:text-white">
                Profile Settings
            </a>

            <form method="POST" action="{{ secure_url(route('logout', [], false)) }}" onsubmit="showLogoutLoading()">
                @csrf
                <button type="submit"
                    class="w-full rounded-2xl px-4 py-3 text-left text-sm text-red-300 hover:bg-red-500/10">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>

<!-- Loading -->
<div id="logoutLoading" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-slate-950/80 backdrop-blur">
    <div class="rounded-3xl bg-slate-900 p-8 text-center">
        <div class="mx-auto h-10 w-10 animate-spin rounded-full border-4 border-cyan-300/20 border-t-cyan-300"></div>
        <p class="mt-4 text-sm text-slate-300">Logging out...</p>
    </div>
</div>

<script>
    function showLogoutLoading() {
        const el = document.getElementById('logoutLoading');
        el.classList.remove('hidden');
        el.classList.add('flex');
    }
</script>
