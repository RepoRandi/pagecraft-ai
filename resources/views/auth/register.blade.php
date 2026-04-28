<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - PageCraft AI</title>
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
                    Start building AI-powered sales pages
                </h1>

                <p class="mt-6 max-w-lg text-lg leading-8 text-slate-300">
                    Create polished landing pages with persuasive copy, structured sections, and export-ready HTML.
                </p>

                <div class="mt-10 grid gap-4">
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-5 backdrop-blur">
                        ✨ Structured landing page generation
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-5 backdrop-blur">
                        🧠 AI-assisted marketing copy
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-5 backdrop-blur">
                        📦 Save, preview, and export pages
                    </div>
                </div>
            </div>
        </section>

        <section class="flex min-h-screen items-center justify-center px-5 py-10 sm:px-8">
            <div class="w-full max-w-md">
                <div class="mb-8 text-center lg:hidden">
                    <p class="text-sm uppercase tracking-[0.35em] text-cyan-300">PageCraft AI</p>
                    <h1 class="mt-4 text-3xl font-black">Create your account</h1>
                    <p class="mt-2 text-sm text-slate-400">
                        Start generating landing pages today.
                    </p>
                </div>

                <div class="rounded-3xl border border-white/10 bg-white/[0.06] p-6 shadow-2xl backdrop-blur sm:p-8">
                    <h2 class="text-3xl font-black">Create Account</h2>
                    <p class="mt-2 text-sm text-slate-400">Sign up to access your AI workspace.</p>

                    @if ($errors->any())
                        <div class="mt-5 rounded-2xl border border-red-500/30 bg-red-500/10 p-4 text-sm text-red-200">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-5">
                        @csrf

                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Name</label>
                            <input name="name" value="{{ old('name') }}" required autofocus
                                class="w-full rounded-2xl border border-white/10 bg-slate-900/80 px-4 py-3 text-white placeholder:text-slate-500 focus:border-cyan-400 focus:ring-cyan-400"
                                placeholder="Your name">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full rounded-2xl border border-white/10 bg-slate-900/80 px-4 py-3 text-white placeholder:text-slate-500 focus:border-cyan-400 focus:ring-cyan-400"
                                placeholder="you@example.com">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Password</label>
                            <input type="password" name="password" required
                                class="w-full rounded-2xl border border-white/10 bg-slate-900/80 px-4 py-3 text-white placeholder:text-slate-500 focus:border-cyan-400 focus:ring-cyan-400"
                                placeholder="Minimum 8 characters">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Confirm Password</label>
                            <input type="password" name="password_confirmation" required
                                class="w-full rounded-2xl border border-white/10 bg-slate-900/80 px-4 py-3 text-white placeholder:text-slate-500 focus:border-cyan-400 focus:ring-cyan-400"
                                placeholder="Repeat password">
                        </div>

                        <button type="submit"
                            class="w-full rounded-2xl bg-gradient-to-r from-cyan-300 to-blue-500 px-5 py-3.5 font-bold text-slate-950 shadow-lg shadow-cyan-500/20 transition hover:scale-[1.01]">
                            Register
                        </button>
                    </form>

                    <p class="mt-6 text-center text-sm text-slate-400">
                        Already have an account?
                        <a href="{{ route('login') }}" class="font-semibold text-cyan-300 hover:text-cyan-200">
                            Login
                        </a>
                    </p>
                </div>
            </div>
        </section>

    </main>
</body>

</html>
