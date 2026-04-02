<section class="space-y-6">
    <header>
        <h2 class="text-lg font-black text-red-500">
            Request Account Deletion
        </h2>

        <p class="mt-1 text-sm font-medium" style="color: var(--text-secondary);">
            If you wish to delete your account, you must submit a request to the administration. Once approved, all of
            your resources and data will be permanently deleted.
        </p>

        @if (session('status') === 'deletion-request-sent')
            <div
                class="mt-4 p-4 bg-emerald-50 text-emerald-600 rounded-2xl border border-emerald-100 font-bold text-xs uppercase tracking-widest animate-fade-in flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                Request sent successfully. Admins will review your request soon.
            </div>
        @elseif (session('status') === 'deletion-request-pending')
            <div
                class="mt-4 p-4 bg-amber-50 text-amber-600 rounded-2xl border border-amber-100 font-bold text-xs uppercase tracking-widest animate-fade-in flex items-center gap-2">
                <i class="fas fa-clock"></i>
                You already have a pending deletion request.
            </div>
        @endif
    </header>

    <button onclick="document.getElementById('confirm-user-deletion').classList.remove('hidden')"
        class="px-6 py-3 bg-red-500/10 text-red-500 border border-red-500/20 rounded-xl font-bold hover:bg-red-500 hover:text-white transition-all text-xs uppercase tracking-widest shadow-sm">
        Request Deletion
    </button>

    <div id="confirm-user-deletion" class="hidden fixed inset-0 z-[2000] overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" aria-hidden="true"
                onclick="document.getElementById('confirm-user-deletion').classList.add('hidden')"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom glass border rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-8"
                style="background-color: var(--bg-secondary); border-color: var(--border-color);">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <h2 class="text-xl font-black" style="color: var(--text-primary);">
                        Are you sure you want to request account deletion?
                    </h2>

                    <p class="mt-4 text-sm font-medium" style="color: var(--text-secondary);">
                        This will send a formal request to our administrators. Your data will remain accessible until
                        the request is processed. Please enter your password to confirm.
                    </p>

                    <div class="mt-6">
                        <label for="password" class="sr-only">Password</label>
                        <input id="password" name="password" type="password"
                            class="w-full px-4 py-3 rounded-xl border focus:outline-none focus:ring-4 focus:ring-red-500/10 transition-all font-medium"
                            style="background-color: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);"
                            placeholder="Current Password" />
                        @error('password', 'userDeletion')
                            <p class="mt-2 text-xs text-red-500 font-bold uppercase">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-8 flex justify-end gap-3">
                        <button type="button"
                            onclick="document.getElementById('confirm-user-deletion').classList.add('hidden')"
                            class="px-6 py-2.5 rounded-xl border font-bold text-sm transition-all"
                            style="background-color: var(--bg-secondary); border-color: var(--border-color); color: var(--text-secondary);">
                            Cancel
                        </button>

                        <button type="submit"
                            class="px-6 py-2.5 bg-red-600 text-white rounded-xl font-bold shadow-lg shadow-red-600/20 hover:brightness-110 transition-all text-sm uppercase tracking-widest">
                            Send Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>