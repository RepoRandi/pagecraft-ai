<section>
    <form method="post" action="{{ secure_url(route('password.update', [], false)) }}" class="space-y-6">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="mb-2 block text-sm font-medium text-slate-300">
                Current Password
            </label>

            <input id="update_password_current_password" name="current_password" type="password"
                autocomplete="current-password"
                class="w-full rounded-2xl border border-white/10 bg-slate-900/80 px-4 py-3 text-white placeholder:text-slate-500 focus:border-cyan-400 focus:ring-cyan-400" />

            @error('current_password', 'updatePassword')
                <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password" class="mb-2 block text-sm font-medium text-slate-300">
                New Password
            </label>

            <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                class="w-full rounded-2xl border border-white/10 bg-slate-900/80 px-4 py-3 text-white placeholder:text-slate-500 focus:border-cyan-400 focus:ring-cyan-400" />

            @error('password', 'updatePassword')
                <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password_confirmation" class="mb-2 block text-sm font-medium text-slate-300">
                Confirm Password
            </label>

            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                autocomplete="new-password"
                class="w-full rounded-2xl border border-white/10 bg-slate-900/80 px-4 py-3 text-white placeholder:text-slate-500 focus:border-cyan-400 focus:ring-cyan-400" />

            @error('password_confirmation', 'updatePassword')
                <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-4">
            <button type="submit"
                class="rounded-2xl bg-gradient-to-r from-cyan-300 to-blue-500 px-6 py-3 font-bold text-slate-950 shadow-lg shadow-cyan-500/20 transition hover:scale-[1.01] hover:brightness-110">
                Update Password
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-emerald-300">
                    Updated successfully.
                </p>
            @endif
        </div>
    </form>
</section>
