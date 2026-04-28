<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PageCraft AI</title>

    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-950 text-white antialiased">
    <main class="min-h-screen grid lg:grid-cols-2">

        <section class="relative hidden overflow-hidden lg:flex items-center justify-center px-12">
            <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-950 to-cyan-950"></div>
            <div class="absolute -left-32 top-20 h-72 w-72 rounded-full bg-cyan-500/20 blur-3xl"></div>
            <div class="absolute bottom-10 right-10 h-72 w-72 rounded-full bg-blue-500/20 blur-3xl"></div>

            <div class="relative max-w-xl">
                <p class="text-sm uppercase tracking-[0.35em] text-cyan-300">PageCraft AI</p>

                <h1 class="mt-6 text-5xl font-black leading-tight xl:text-6xl">
                    Build high-converting sales pages with AI
                </h1>

                <p class="mt-6 max-w-lg text-lg leading-8 text-slate-300">
                    Transform your product idea into a persuasive, structured, and exportable landing page in seconds.
                </p>

                <div class="mt-10 grid gap-4">
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-5 backdrop-blur">
                        🚀 Generate marketing-ready copy instantly
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-5 backdrop-blur">
                        🎯 Designed for founders, marketers, and product teams
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-5 backdrop-blur">
                        ⚡ Export ready-to-use landing page HTML
                    </div>
                </div>
            </div>
        </section>

        <section class="flex min-h-screen items-center justify-center px-5 py-10 sm:px-8">
            <div class="w-full max-w-md">
                <div class="mb-8 text-center lg:hidden">
                    <p class="text-sm uppercase tracking-[0.35em] text-cyan-300">PageCraft AI</p>
                    <h1 class="mt-4 text-3xl font-black">AI Sales Page Generator</h1>
                    <p class="mt-2 text-sm text-slate-400">
                        Generate beautiful sales pages faster.
                    </p>
                </div>

                <div class="rounded-3xl border border-white/10 bg-white/[0.06] p-6 shadow-2xl backdrop-blur sm:p-8">
                    <h2 class="text-3xl font-black">Welcome back 👋</h2>
                    <p class="mt-2 text-sm text-slate-400">Login to continue your workspace.</p>

                    @if ($errors->any())
                        <div class="mt-5 rounded-2xl border border-red-500/30 bg-red-500/10 p-4 text-sm text-red-200">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form id="loginForm" method="POST" action="{{ secure_url(route('login', [], false)) }}"
                        class="mt-6 space-y-5">
                        @csrf

                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                class="w-full rounded-2xl border border-white/10 bg-slate-900/80 px-4 py-3 text-white placeholder:text-slate-500 focus:border-cyan-400 focus:ring-cyan-400"
                                placeholder="you@example.com">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Password</label>
                            <input type="password" name="password" required
                                class="w-full rounded-2xl border border-white/10 bg-slate-900/80 px-4 py-3 text-white placeholder:text-slate-500 focus:border-cyan-400 focus:ring-cyan-400"
                                placeholder="••••••••">
                        </div>

                        <button id="loginButton" type="submit"
                            class="flex w-full items-center justify-center gap-3 rounded-2xl bg-gradient-to-r from-cyan-300 to-blue-500 px-5 py-3.5 font-bold text-slate-950 shadow-lg shadow-cyan-500/20 transition hover:scale-[1.01] disabled:cursor-not-allowed disabled:opacity-70">
                            <span id="loginSpinner"
                                class="hidden h-5 w-5 animate-spin rounded-full border-2 border-slate-950/30 border-t-slate-950"></span>
                            <span id="loginText">Login</span>
                        </button>
                    </form>

                    <p class="mt-6 text-center text-sm text-slate-400">
                        Don’t have an account?
                        <a href="{{ secure_url(route('register', [], false)) }}"
                            class="font-semibold text-cyan-300 hover:text-cyan-200">
                            Register
                        </a>
                    </p>
                </div>
            </div>
        </section>
    </main>

    <div id="loadingOverlay"
        class="fixed inset-0 z-[9999] hidden items-center justify-center bg-slate-950/80 px-4 backdrop-blur-sm">
        <div class="w-full max-w-sm rounded-3xl border border-white/10 bg-slate-900 p-8 text-center shadow-2xl">
            <div class="mx-auto h-12 w-12 animate-spin rounded-full border-4 border-cyan-300/20 border-t-cyan-300">
            </div>
            <h3 class="mt-6 text-xl font-black">Signing you in...</h3>
            <p class="mt-2 text-sm text-slate-400">
                Please wait while we prepare your workspace.
            </p>
        </div>
    </div>

    <script>
        const loginForm = document.getElementById('loginForm');
        const loginButton = document.getElementById('loginButton');
        const loginSpinner = document.getElementById('loginSpinner');
        const loginText = document.getElementById('loginText');
        const loadingOverlay = document.getElementById('loadingOverlay');

        loginForm?.addEventListener('submit', () => {
            loginButton.disabled = true;
            loginSpinner.classList.remove('hidden');
            loginText.textContent = 'Logging in...';

            loadingOverlay.classList.remove('hidden');
            loadingOverlay.classList.add('flex');
        });
    </script>
</body>

</html>
