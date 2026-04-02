@extends('layouts.dashboard')

@section('title', 'Create User')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Create New User</h1>
                <p class="text-slate-600 text-sm">Add a new administrator, mentor, parent, or student.</p>
            </div>
            <a href="{{ route('admin.users.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-50 transition-colors shadow-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Users</span>
            </a>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6"
            id="createUserForm">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Profile & Role -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Profile Picture -->
                    <div class="glass rounded-2xl p-6 text-center">
                        <h3 class="font-bold text-slate-900 mb-4 text-left">Profile Image</h3>

                        <div class="relative w-32 h-32 mx-auto mb-4 group cursor-pointer">
                            <div
                                class="w-full h-full rounded-full bg-slate-100 border-4 border-white shadow-md overflow-hidden flex items-center justify-center relative">
                                <img id="previewImage" src="#" alt="Preview" class="w-full h-full object-cover hidden">
                                <i id="defaultIcon" class="fas fa-camera text-3xl text-slate-300"></i>
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
                            <label class="relative block cursor-pointer group">
                                <input type="radio" name="role" value="Admin" class="peer sr-only" {{ old('role') == 'Admin' ? 'checked' : '' }} onchange="toggleParentFields()">
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
                                <input type="radio" name="role" value="Mentor" class="peer sr-only" {{ old('role') == 'Mentor' ? 'checked' : '' }} onchange="toggleParentFields()">
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
                                <input type="radio" name="role" value="Parent" class="peer sr-only" {{ old('role') == 'Parent' ? 'checked' : '' }} onchange="toggleParentFields()">
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
                                <input type="radio" name="role" value="Child" class="peer sr-only" {{ old('role') == 'Child' ? 'checked' : '' }} onchange="toggleParentFields()">
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
                                <input type="text" name="first_name" value="{{ old('first_name') }}" required
                                    class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all placeholder:text-slate-400"
                                    placeholder="Enter first name">
                                @error('first_name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-slate-700">Last Name <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" required
                                    class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all placeholder:text-slate-400"
                                    placeholder="Enter last name">
                                @error('last_name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-slate-700">Email Address <span
                                        class="text-red-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all placeholder:text-slate-400"
                                    placeholder="user@example.com">
                                @error('email') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-slate-700">Phone Number</label>
                                <input type="tel" name="phone_number" value="{{ old('phone_number') }}"
                                    class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all placeholder:text-slate-400"
                                    placeholder="+1 (555) 000-0000">
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-slate-700">Date of Birth</label>
                                <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                                    class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all text-slate-600">
                                @error('date_of_birth') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-slate-700">Gender <span
                                        class="text-red-500">*</span></label>
                                <select name="gender" required
                                    class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all text-slate-600 appearance-none">
                                    <option value="" disabled {{ !old('gender') ? 'selected' : '' }}>Select Gender</option>
                                    <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                                @error('gender') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-1 md:col-span-2">
                                <label class="text-sm font-semibold text-slate-700">Address</label>
                                <textarea name="address" rows="2"
                                    class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all placeholder:text-slate-400 resize-none"
                                    placeholder="Full residential address">{{ old('address') }}</textarea>
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
                                <p class="text-xs text-slate-500">Set initial login credentials</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-slate-700">Password <span
                                        class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="password" name="password" id="password" required value="password"
                                        class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all placeholder:text-slate-400"
                                        placeholder="••••••••">
                                    <i onclick="togglePasswordVisibility('password', this)"
                                        class="fas fa-eye absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 cursor-pointer hover:text-[#0B4D73]"></i>
                                </div>
                                @error('password') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-slate-700">Confirm Password <span
                                        class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" id="password_confirmation" required value="password"
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
                                <label class="text-sm font-semibold text-slate-700">Parent Email <span
                                        class="text-red-500">*</span></label>
                                <input type="email" name="parent_email" value="{{ old('parent_email') }}"
                                    class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all placeholder:text-slate-400"
                                    placeholder="Enter parent's email to link account">
                                <p class="text-[10px] text-slate-500 mt-1"><i class="fas fa-info-circle"></i> If parent
                                    exists, account will be linked automatically.</p>
                                @error('parent_email') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-slate-700">Parent First Name</label>
                                <input type="text" name="parent_first_name" value="{{ old('parent_first_name') }}"
                                    class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all placeholder:text-slate-400"
                                    placeholder="John">
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-slate-700">Parent Last Name</label>
                                <input type="text" name="parent_last_name" value="{{ old('parent_last_name') }}"
                                    class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all placeholder:text-slate-400"
                                    placeholder="Doe">
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-slate-700">Relationship</label>
                                <input type="text" name="relationship" value="{{ old('relationship') }}"
                                    class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all placeholder:text-slate-400"
                                    placeholder="Mother, Father, Guardian">
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-slate-700">Parent Phone Number</label>
                                <input type="tel" name="parent_phone_number" value="{{ old('parent_phone_number') }}"
                                    class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all placeholder:text-slate-400"
                                    placeholder="+1 (555) 000-0000">
                            </div>

                            <div class="space-y-1 md:col-span-2">
                                <label class="text-sm font-semibold text-slate-700">Parent Address</label>
                                <textarea name="parent_address" rows="2"
                                    class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all placeholder:text-slate-400 resize-none"
                                    placeholder="Full residential address">{{ old('parent_address') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-4">
                        <button type="submit"
                            class="px-8 py-3 rounded-xl bg-[#0B4D73] text-white font-bold text-lg hover:bg-[#093e5d] transition-colors shadow-lg shadow-blue-900/20 flex items-center gap-2">
                            <span>Create User</span>
                            <i class="fas fa-arrow-right text-sm"></i>
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
            } else {
                preview.src = "";
                preview.classList.add('hidden');
                defaultIcon.classList.remove('hidden');
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