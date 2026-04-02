<section>
    <header>
        <h2 class="text-xl font-black" style="color: var(--text-primary);">
            Profile Information
        </h2>
        <p class="mt-1 text-sm font-medium" style="color: var(--text-secondary);">
            Update your account's personal details and profile picture.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-8 space-y-8" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Profile Picture Section -->
        <div class="flex flex-col md:flex-row items-center gap-8 p-6 rounded-3xl bg-opacity-5 border border-dashed"
            style="background-color: var(--text-primary); border-color: var(--border-color);">
            <div class="relative group">
                <div class="w-32 h-32 rounded-3xl overflow-hidden border-4 shadow-xl transition-all group-hover:scale-105"
                    style="border-color: var(--bg-secondary);">
                    @if($user->profile_picture)
                        <img id="avatar-preview" src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile"
                            class="w-full h-full object-cover">
                    @else
                        <div id="avatar-placeholder"
                            class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#0B4D73] to-blue-400 text-white text-4xl font-black">
                            {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                        </div>
                        <img id="avatar-preview" src="#" alt="Profile" class="w-full h-full object-cover hidden">
                    @endif
                </div>
                <label for="profile_picture"
                    class="absolute -bottom-2 -right-2 w-10 h-10 bg-[#0B4D73] text-white rounded-xl shadow-lg flex items-center justify-center cursor-pointer hover:scale-110 transition-transform border-4"
                    style="border-color: var(--bg-secondary);">
                    <i class="fas fa-camera text-sm"></i>
                    <input id="profile_picture" name="profile_picture" type="file" class="hidden" accept="image/*"
                        onchange="previewImage(this)">
                </label>
            </div>
            <div class="flex-1 text-center md:text-left">
                <h4 class="font-black text-sm uppercase tracking-widest" style="color: var(--text-primary);">Display
                    Image</h4>
                <p class="text-xs font-medium mt-1" style="color: var(--text-secondary);">Click the camera icon to
                    upload a new avatar. JPG, PNG or GIF. Max 2MB.</p>
                @error('profile_picture')
                    <p class="mt-2 text-xs text-red-500 font-bold uppercase">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- First Name -->
            <div>
                <label for="first_name" class="block text-[10px] font-black uppercase tracking-widest mb-2"
                    style="color: var(--text-secondary);">First Name</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30"
                        style="color: var(--text-primary);"><i class="fas fa-user"></i></span>
                    <input id="first_name" name="first_name" type="text"
                        class="w-full pl-10 pr-4 py-3 rounded-xl border focus:outline-none focus:ring-4 focus:ring-[#0B4D73]/10 transition-all font-medium {{ $user->hasRole('Child') ? 'opacity-50 cursor-not-allowed' : '' }}"
                        style="background-color: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);"
                        value="{{ old('first_name', $user->first_name) }}" required autofocus {{ $user->hasRole('Child') ? 'disabled' : '' }} />
                </div>
                @if($user->hasRole('Child'))
                    <p class="mt-2 text-[8px] text-slate-400 font-black uppercase tracking-widest">Locked for Students</p>
                @endif
                @error('first_name')
                    <p class="mt-2 text-[10px] text-red-500 font-black uppercase">{{ $message }}</p>
                @enderror
            </div>

            <!-- Last Name -->
            <div>
                <label for="last_name" class="block text-[10px] font-black uppercase tracking-widest mb-2"
                    style="color: var(--text-secondary);">Last Name</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30"
                        style="color: var(--text-primary);"><i class="fas fa-user"></i></span>
                    <input id="last_name" name="last_name" type="text"
                        class="w-full pl-10 pr-4 py-3 rounded-xl border focus:outline-none focus:ring-4 focus:ring-[#0B4D73]/10 transition-all font-medium {{ $user->hasRole('Child') ? 'opacity-50 cursor-not-allowed' : '' }}"
                        style="background-color: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);"
                        value="{{ old('last_name', $user->last_name) }}" required {{ $user->hasRole('Child') ? 'disabled' : '' }} />
                </div>
                @if($user->hasRole('Child'))
                    <p class="mt-2 text-[8px] text-slate-400 font-black uppercase tracking-widest">Locked for Students</p>
                @endif
                @error('last_name')
                    <p class="mt-2 text-[10px] text-red-500 font-black uppercase">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="md:col-span-2">
                <label for="email" class="block text-[10px] font-black uppercase tracking-widest mb-2"
                    style="color: var(--text-secondary);">Email Address</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30"
                        style="color: var(--text-primary);"><i class="fas fa-envelope"></i></span>
                    <input id="email" name="email" type="email"
                        class="w-full pl-10 pr-4 py-3 rounded-xl border focus:outline-none focus:ring-4 focus:ring-[#0B4D73]/10 transition-all font-medium"
                        style="background-color: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);"
                        value="{{ old('email', $user->email) }}" required />
                </div>
                @error('email')
                    <p class="mt-2 text-[10px] text-red-500 font-black uppercase">{{ $message }}</p>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                    <div class="mt-4 p-4 rounded-xl bg-amber-500/10 border border-amber-500/20">
                        <p class="text-sm font-bold" style="color: var(--text-primary);">
                            Your email is unverified.
                            <button form="send-verification"
                                class="ml-2 underline text-xs font-black uppercase tracking-wider hover:brightness-110"
                                style="color: var(--primary-500);">
                                Resend verification email
                            </button>
                        </p>
                    </div>
                @endif
            </div>

            <!-- Phone Number -->
            <div>
                <label for="phone_number" class="block text-[10px] font-black uppercase tracking-widest mb-2"
                    style="color: var(--text-secondary);">Phone Number</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30"
                        style="color: var(--text-primary);"><i class="fas fa-phone"></i></span>
                    <input id="phone_number" name="phone_number" type="text"
                        class="w-full pl-10 pr-4 py-3 rounded-xl border focus:outline-none focus:ring-4 focus:ring-[#0B4D73]/10 transition-all font-medium"
                        style="background-color: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);"
                        value="{{ old('phone_number', $user->phone_number) }}" placeholder="+1 (555) 000-0000" />
                </div>
                @error('phone_number')
                    <p class="mt-2 text-[10px] text-red-500 font-black uppercase">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date of Birth -->
            <div>
                <label for="date_of_birth" class="block text-[10px] font-black uppercase tracking-widest mb-2"
                    style="color: var(--text-secondary);">Date of Birth</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30"
                        style="color: var(--text-primary);"><i class="fas fa-calendar"></i></span>
                    <input id="date_of_birth" name="date_of_birth" type="date"
                        class="w-full pl-10 pr-4 py-3 rounded-xl border focus:outline-none focus:ring-4 focus:ring-[#0B4D73]/10 transition-all font-medium {{ $user->hasRole('Child') ? 'opacity-50 cursor-not-allowed' : '' }}"
                        style="background-color: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);"
                        value="{{ old('date_of_birth', $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('Y-m-d') : '') }}"
                        {{ $user->hasRole('Child') ? 'disabled' : '' }} />
                </div>
                @if($user->hasRole('Child'))
                    <p class="mt-2 text-[8px] text-slate-400 font-black uppercase tracking-widest">Locked for Students</p>
                @endif
                @error('date_of_birth')
                    <p class="mt-2 text-[10px] text-red-500 font-black uppercase">{{ $message }}</p>
                @enderror
            </div>

            <!-- Gender -->
            <div>
                <label for="gender" class="block text-[10px] font-black uppercase tracking-widest mb-2"
                    style="color: var(--text-secondary);">Gender</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30"
                        style="color: var(--text-primary);"><i class="fas fa-venus-mars"></i></span>
                    <select id="gender" name="gender"
                        class="w-full pl-10 pr-4 py-3 rounded-xl border focus:outline-none focus:ring-4 focus:ring-[#0B4D73]/10 transition-all font-medium appearance-none"
                        style="background-color: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);"
                        required>
                        <option value="" disabled {{ !old('gender', $user->gender) ? 'selected' : '' }}>Select Gender
                        </option>
                        <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Female
                        </option>
                    </select>
                </div>
                @error('gender')
                    <p class="mt-2 text-[10px] text-red-500 font-black uppercase">{{ $message }}</p>
                @enderror
            </div>

            <!-- Address -->
            <div class="md:col-span-2">
                <label for="address" class="block text-[10px] font-black uppercase tracking-widest mb-2"
                    style="color: var(--text-secondary);">Home Address</label>
                <div class="relative">
                    <span class="absolute left-4 top-7 opacity-30" style="color: var(--text-primary);"><i
                            class="fas fa-map-marker-alt"></i></span>
                    <textarea id="address" name="address" rows="3"
                        class="w-full pl-10 pr-4 py-3 rounded-xl border focus:outline-none focus:ring-4 focus:ring-[#0B4D73]/10 transition-all font-medium resize-none"
                        style="background-color: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);">{{ old('address', $user->address) }}</textarea>
                </div>
                @error('address')
                    <p class="mt-2 text-[10px] text-red-500 font-black uppercase">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4 border-t" style="border-color: var(--border-color);">
            <button type="submit"
                class="px-8 py-4 bg-[#0B4D73] text-white rounded-2xl font-black shadow-lg shadow-[#0B4D73]/20 hover:scale-105 active:scale-95 transition-all text-xs uppercase tracking-widest">
                Save Profile
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                    class="text-xs font-black uppercase text-emerald-500">
                    <i class="fas fa-check-circle mr-1"></i> Profile Updated Successfully
                </p>
            @endif
        </div>
    </form>
</section>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                const preview = document.getElementById('avatar-preview');
                const placeholder = document.getElementById('avatar-placeholder');

                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (placeholder) placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>