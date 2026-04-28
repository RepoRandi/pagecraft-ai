<x-app-layout>
    <div class="min-h-screen bg-slate-950 text-white">
        <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">

            <div class="mb-8">
                <p class="text-sm uppercase tracking-[0.35em] text-cyan-300">
                    Account Settings
                </p>
                <h1 class="mt-3 text-3xl font-black md:text-4xl">
                    Manage your profile
                </h1>
                <p class="mt-3 max-w-2xl text-slate-400">
                    Update your account information, password, and security preferences.
                </p>
            </div>

            @if (session('status'))
                <div
                    class="mb-6 rounded-2xl border border-emerald-400/20 bg-emerald-400/10 px-5 py-4 text-sm text-emerald-200">
                    {{ session('status') }}
                </div>
            @endif

            <div class="grid gap-6">

                <section
                    class="overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-br from-white/[0.07] to-white/[0.03] shadow-2xl shadow-slate-950/50 backdrop-blur-xl">
                    <div class="border-b border-white/10 px-6 py-5">
                        <h2 class="text-lg font-bold">Profile Information</h2>
                        <p class="mt-1 text-sm text-slate-400">
                            Update your name and email address.
                        </p>
                    </div>

                    <div class="p-6">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </section>

                <section
                    class="overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-br from-white/[0.07] to-white/[0.03] shadow-2xl shadow-slate-950/50 backdrop-blur-xl">
                    <div class="border-b border-white/10 px-6 py-5">
                        <h2 class="text-lg font-bold">Update Password</h2>
                        <p class="mt-1 text-sm text-slate-400">
                            Use a strong password to keep your account secure.
                        </p>
                    </div>

                    <div class="p-6">
                        @include('profile.partials.update-password-form')
                    </div>
                </section>

                <section
                    class="overflow-hidden rounded-3xl border border-red-400/20 bg-gradient-to-br from-red-500/[0.08] to-white/[0.03] shadow-2xl shadow-slate-950/50 backdrop-blur-xl">
                    <div class="border-b border-red-400/20 px-6 py-5">
                        <h2 class="text-lg font-bold text-red-200">Delete Account</h2>
                        <p class="mt-1 text-sm text-red-100/70">
                            Permanently delete your account and all related data.
                        </p>
                    </div>

                    <div class="p-6">
                        @include('profile.partials.delete-user-form')
                    </div>
                </section>

            </div>
        </div>
    </div>
</x-app-layout>
