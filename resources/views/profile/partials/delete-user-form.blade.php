<section x-data="{ confirmingUserDeletion: false }">
    <div class="rounded-2xl border border-red-400/20 bg-red-500/10 p-5">
        <h3 class="text-base font-bold text-red-200">
            Danger Zone
        </h3>

        <p class="mt-2 max-w-2xl text-sm leading-6 text-red-100/70">
            Once your account is deleted, all of its resources and data will be permanently removed.
            Please download or export anything you want to keep before continuing.
        </p>

        <button type="button" @click="confirmingUserDeletion = true"
            class="mt-5 rounded-2xl border border-red-400/30 bg-red-500/10 px-6 py-3 font-bold text-red-200 transition hover:bg-red-500/20">
            Delete Account
        </button>
    </div>

    <div x-show="confirmingUserDeletion" x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 px-4 backdrop-blur-sm"
        style="display: none;">

        <div @click.outside="confirmingUserDeletion = false"
            class="w-full max-w-lg overflow-hidden rounded-3xl border border-white/10 bg-slate-900 shadow-2xl shadow-black/40">

            <div class="border-b border-white/10 px-6 py-5">
                <h2 class="text-xl font-black text-white">
                    Are you sure?
                </h2>
                <p class="mt-2 text-sm leading-6 text-slate-400">
                    This action cannot be undone. Please enter your password to confirm account deletion.
                </p>
            </div>

            <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                @csrf
                @method('delete')

                <label for="password" class="mb-2 block text-sm font-medium text-slate-300">
                    Password
                </label>

                <input id="password" name="password" type="password" placeholder="Enter your password"
                    class="w-full rounded-2xl border border-white/10 bg-slate-950 px-4 py-3 text-white placeholder:text-slate-500 focus:border-red-400 focus:ring-red-400" />

                @error('password', 'userDeletion')
                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                @enderror

                <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <button type="button" @click="confirmingUserDeletion = false"
                        class="rounded-2xl border border-white/10 px-6 py-3 font-semibold text-slate-300 transition hover:bg-white/5 hover:text-white">
                        Cancel
                    </button>

                    <button type="submit"
                        class="rounded-2xl bg-red-500 px-6 py-3 font-bold text-white transition hover:bg-red-400">
                        Delete Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
