<section>
    <header>
        <h2 class="text-lg font-black" style="color: var(--text-primary);">
            Update Password
        </h2>

        <p class="mt-1 text-sm font-medium" style="color: var(--text-secondary);">
            Ensure your account is using a long, random password to stay secure.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password"
                class="block text-xs font-black uppercase tracking-widest mb-2"
                style="color: var(--text-secondary);">Current Password</label>
            <input id="update_password_current_password" name="current_password" type="password"
                class="w-full px-4 py-3 rounded-xl border focus:outline-none focus:ring-4 focus:ring-[#0B4D73]/10 transition-all font-medium"
                style="background-color: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);"
                autocomplete="current-password" />
            @error('current_password', 'updatePassword')
                <p class="mt-2 text-xs text-red-500 font-bold uppercase">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password" class="block text-xs font-black uppercase tracking-widest mb-2"
                style="color: var(--text-secondary);">New Password</label>
            <input id="update_password_password" name="password" type="password"
                class="w-full px-4 py-3 rounded-xl border focus:outline-none focus:ring-4 focus:ring-[#0B4D73]/10 transition-all font-medium"
                style="background-color: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);"
                autocomplete="new-password" />
            @error('password', 'updatePassword')
                <p class="mt-2 text-xs text-red-500 font-bold uppercase">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password_confirmation"
                class="block text-xs font-black uppercase tracking-widest mb-2"
                style="color: var(--text-secondary);">Confirm Password</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="w-full px-4 py-3 rounded-xl border focus:outline-none focus:ring-4 focus:ring-[#0B4D73]/10 transition-all font-medium"
                style="background-color: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);"
                autocomplete="new-password" />
            @error('password_confirmation', 'updatePassword')
                <p class="mt-2 text-xs text-red-500 font-bold uppercase">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit"
                class="px-8 py-3 bg-[#0B4D73] text-white rounded-xl font-bold shadow-lg shadow-[#0B4D73]/20 hover:scale-105 active:scale-95 transition-all text-sm uppercase tracking-widest">
                Update Password
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-black uppercase text-emerald-500">Saved.</p>
            @endif
        </div>
    </form>
</section>