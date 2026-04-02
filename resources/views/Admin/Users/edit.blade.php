@extends('layouts.dashboard')

@section('title', 'Edit User')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Edit User</h1>
                <p class="text-slate-600 text-sm">Update user account details and permissions.</p>
            </div>
            <a href="{{ route('admin.users.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-50 transition-colors shadow-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Users</span>
            </a>
        </div>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6" id="editUserForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Profile & Role -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Profile Picture -->
                    <div class="glass rounded-2xl p-6 text-center">
                        <h3 class="font-bold text-slate-900 mb-4 text-left">Profile Image</h3>

                        <div class="relative w-32 h-32 mx-auto mb-4 group cursor-pointer">
                            <div
                                class="w-full h-full rounded-full bg-slate-100 border-4 border-white shadow-md overflow-hidden flex items-center justify-center relative">
                                <img id="previewImage"
                                    src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : '#' }}"
                                    alt="Preview"
                                    class="w-full h-full object-cover {{ $user->profile_picture ? '' : 'hidden' }}">
                                <i id="defaultIcon"
                                    class="fas fa-camera text-3xl text-slate-300 {{ $user->profile_picture ? 'hidden' : '' }}"></i>
                            </div>
                            <div
                                class="absolute inset-0 bg-black/40 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <i class="fas fa-edit text-white"></i>
                            </div>
                            <input type="file" name="profile_picture" id="profile_picture" accept="image/*"
                                class="absolute inset-0 opacity-0 cursor-pointer" onchange="previewFile(this)">
                        </div>
                        <p class="text-xs text-slate-500">Click to upload. JPG, PNG or GIF (Max 2MB)</p>
                        @error('profile_picture')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role Selection -->
                    <div class="glass rounded-2xl p-6">
                        <h3 class="font-bold text-slate-900 mb-4">Account Role</h3>
                        <div class="space-y-3">
                            @php $currentRole = old('role', $user->roles->first()?->name ?? ''); @endphp

                            <label class="relative block cursor-pointer group">
                                <input type="radio" name="role" value="Admin" class="peer sr-only" {{ $currentRole == 'Admin' ? 'checked' : '' }} onchange="toggleParentFields()">
                                <div
                                    class="p-4 rounded-xl border-2 border-slate-100 peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center font-bold">
                                        <i class="fas fa-user-shield"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900">Admin</p>
                                        <p class="text-xs text-slate-500">Full system access</p>
                                    </div>
                                </div>
                            </label>

                            <label class="relative block cursor-pointer group">
                                <input type="radio" name="role" value="Mentor" class="peer sr-only" {{ $currentRole == 'Mentor' ? 'checked' : '' }} onchange="toggleParentFields()">
                                <div
                                    class="p-4 rounded-xl border-2 border-slate-100 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900">Mentor</p>
                                        <p class="text-xs text-slate-500">Manage students & classes</p>
                                    </div>
                                </div>
                            </label>

                            <label class="relative block cursor-pointer group">
                                <input type="radio" name="role" value="Parent" class="peer sr-only" {{ $currentRole == 'Parent' ? 'checked' : '' }} onchange="toggleParentFields()">
                                <div
                                    class="p-4 rounded-xl border-2 border-slate-100 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold">
                                        <i class="fas fa-user-friends"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900">Parent</p>
                                        <p class="text-xs text-slate-500">View child progress</p>
                                    </div>
                                </div>
                            </label>

                            <label class="relative block cursor-pointer group">
                                <input type="radio" name="role" value="Child" class="peer sr-only" {{ $currentRole == 'Child' ? 'checked' : '' }} onchange="toggleParentFields()">
                                <div
                                    class="p-4 rounded-xl border-2 border-slate-100 peer-checked:border-amber-500 peer-checked:bg-amber-50 transition-all flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center font-bold">
                                        <i class="fas fa-child"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900">Student / Child</p>
                                        <p class="text-xs text-slate-500">Access learning dashboard</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                        @error('role')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    @if($user->unique_number)
                        <!-- Unique ID (for children) -->
                        <div class="glass rounded-2xl p-6 border-l-4 border-l-amber-500">
                            <h3 class="font-bold text-slate-900 mb-1">Student ID</h3>
                            <p class="text-xs text-slate-500 mb-2">Unique identifier for this student</p>
                            <p class="font-mono text-xl font-bold text-amber-600 select-all">{{ $user->unique_number }}</p>
                        </div>
                    @endif
                </div>

                <!-- Right Column: Details Form -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Personal Info -->
                    <div class="glass rounded-2xl p-6">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                            <div class="w-10 h-10 rounded-full bg-blue-50 text-[#0B4D73] flex items-center justify-center">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900">Personal Information</h3>
                                <p class="text-xs text-slate-500">Basic identification details</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-slate-700">First Name <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}"
                                    required
                                    class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all placeholder:text-slate-400"
                                    placeholder="Enter first name">
                                @error('first_name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-slate-700">Last Name <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                                    required
                                    class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all placeholder:text-slate-400"
                                    placeholder="Enter last name">
                                @error('last_name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-slate-700">Email Address <span
                                        class="text-red-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                    class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all placeholder:text-slate-400"
                                    placeholder="user@example.com">
                                @error('email') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-slate-700">Phone Number</label>
                                <input type="tel" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                                    class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all placeholder:text-slate-400"
                                    placeholder="+1 (555) 000-0000">
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-slate-700">Date of Birth</label>
                                <input type="date" name="date_of_birth"
                                    value="{{ old('date_of_birth', $user->date_of_birth) }}"
                                    class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all text-slate-600">
                                @error('date_of_birth') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-slate-700">Gender <span class="text-red-500">*</span></label>
                                <select name="gender" required
                                    class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all text-slate-600 appearance-none">
                                    <option value="" disabled {{ !old('gender', $user->gender) ? 'selected' : '' }}>Select Gender</option>
                                    <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                                @error('gender') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-1 md:col-span-2">
                                <label class="text-sm font-semibold text-slate-700">Address</label>
                                <textarea name="address" rows="2"
                                    class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all placeholder:text-slate-400 resize-none"
                                    placeholder="Full residential address">{{ old('address', $user->address) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Password Info -->
                    <div class="glass rounded-2xl p-6">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                            <div
                                class="w-10 h-10 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center">
                                <i class="fas fa-lock"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900">Security</h3>
                                <p class="text-xs text-slate-500">Update login password (leave blank to keep current)</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-slate-700">New Password</label>
                                <div class="relative">
                                    <input type="password" name="password" id="password"
                                        class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all placeholder:text-slate-400"
                                        placeholder="••••••••">
                                    <i onclick="togglePasswordVisibility('password', this)"
                                        class="fas fa-eye absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 cursor-pointer hover:text-[#0B4D73]"></i>
                                </div>
                                @error('password') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-slate-700">Confirm New Password</label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all placeholder:text-slate-400"
                                        placeholder="••••••••">
                                    <i onclick="togglePasswordVisibility('password_confirmation', this)"
                                        class="fas fa-eye absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 cursor-pointer hover:text-[#0B4D73]"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Parent Info (Hidden by default, shown for Child role) -->
                    <div id="parentFields" class="glass rounded-2xl p-6 hidden ring-2 ring-amber-100">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                            <div class="w-10 h-10 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center">
                                <i class="fas fa-user-friends"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900">Parent/Guardian Details</h3>
                                <p class="text-xs text-slate-500">Required for student accounts</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-1 md:col-span-2">
                                @if($user->parent)
                                    <div class="p-3 bg-blue-50 border border-blue-100 rounded-xl mb-3 flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-blue-200 text-blue-700 flex items-center justify-center text-xs font-bold">
                                            {{ substr($user->parent->first_name, 0, 1) }}{{ substr($user->parent->last_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-blue-900">current Parent:
                                                {{ $user->parent->first_name }} {{ $user->parent->last_name }}</p>
                                            <p class="text-xs text-blue-600">{{ $user->parent->email }}</p>
                                        </div>
                                    </div>
                                @endif

                                <label class="text-sm font-semibold text-slate-700">Parent Email <span
                                        class="text-red-500">*</span></label>
                                <input type="email" name="parent_email"
                                    value="{{ old('parent_email', $user->parent->email ?? '') }}"
                                    class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all placeholder:text-slate-400"
                                    placeholder="Enter parent's email to link account">
                                <p class="text-[10px] text-slate-500 mt-1"><i class="fas fa-info-circle"></i> Change email
                                    to re-link to a different parent or create a new one.</p>
                                @error('parent_email') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-slate-700">Parent First Name</label>
                                <input type="text" name="parent_first_name"
                                    value="{{ old('parent_first_name', $user->parent->first_name ?? '') }}"
                                    class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all placeholder:text-slate-400"
                                    placeholder="John">
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-slate-700">Parent Last Name</label>
                                <input type="text" name="parent_last_name"
                                    value="{{ old('parent_last_name', $user->parent->last_name ?? '') }}"
                                    class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all placeholder:text-slate-400"
                                    placeholder="Doe">
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-slate-700">Relationship</label>
                                <input type="text" name="relationship"
                                    value="{{ old('relationship', $user->relationship) }}"
                                    class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all placeholder:text-slate-400"
                                    placeholder="Mother, Father, Guardian">
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-slate-700">Parent Phone Number</label>
                                <input type="tel" name="parent_phone_number"
                                    value="{{ old('parent_phone_number', $user->parent->phone_number ?? '') }}"
                                    class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all placeholder:text-slate-400"
                                    placeholder="+1 (555) 000-0000">
                            </div>

                            <div class="space-y-1 md:col-span-2">
                                <label class="text-sm font-semibold text-slate-700">Parent Address</label>
                                <textarea name="parent_address" rows="2"
                                    class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all placeholder:text-slate-400 resize-none"
                                    placeholder="Full residential address">{{ old('parent_address', $user->parent->address ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-4 gap-3">
                        <a href="{{ route('admin.users.index') }}"
                            class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-colors">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-8 py-3 rounded-xl bg-[#0B4D73] text-white font-bold text-lg hover:bg-[#093e5d] transition-colors shadow-lg shadow-blue-900/20 flex items-center gap-2">
                            <span>Update User</span>
                            <i class="fas fa-save text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function previewFile(input) {
            var preview = document.getElementById('previewImage');
            var defaultIcon = document.getElementById('defaultIcon');
            var file = input.files[0];
            var reader = new FileReader();

            reader.onloadend = function () {
                preview.src = reader.result;
                preview.classList.remove('hidden');
                defaultIcon.classList.add('hidden');
            }

            if (file) {
                reader.readAsDataURL(file);
            }
        }

        function toggleParentFields() {
            const role = document.querySelector('input[name="role"]:checked')?.value;
            const parentFields = document.getElementById('parentFields');

            if (role === 'Child') {
                parentFields.classList.remove('hidden');
                // Add required attribute to parent email if visible
                document.querySelector('input[name="parent_email"]').setAttribute('required', 'required');
            } else {
                parentFields.classList.add('hidden');
                document.querySelector('input[name="parent_email"]').removeAttribute('required');
            }
        }

        function togglePasswordVisibility(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Run on load to handle validation errors state
        document.addEventListener('DOMContentLoaded', toggleParentFields);
    </script>
@endsection