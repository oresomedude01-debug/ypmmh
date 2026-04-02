@extends('layouts.auth')
@section('title', 'Join YPMMH')

@section('styles')
    <style>
        .role-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }

        .role-card.active {
            background: #0B4D73;
            color: white;
            transform: scale(1.05);
            box-shadow: 0 20px 25px -5px rgba(11, 77, 115, 0.3);
        }

        .role-card.active i {
            color: white !important;
        }

        .role-card.active p {
            color: rgba(255, 255, 255, 0.8) !important;
        }

        .role-card.active h3 {
            color: white !important;
        }

        .step-content {
            transition: all 0.5s ease;
        }
    </style>
@endsection

@section('content')
    <div class="w-full max-w-2xl px-4 py-8 md:py-12">
        <div class="glass p-6 sm:p-10 md:p-12 rounded-[2.5rem] animate-auth relative overflow-hidden">
            <!-- Back Link -->
            <div class="mb-10 text-left">
                <a href="/"
                    class="inline-flex items-center text-xs font-black uppercase tracking-widest text-slate-400 hover:text-primary transition-colors group">
                    <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i> Back to Home
                </a>
            </div>

            <div class="text-center mb-12">
                <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-3">Create <span
                        class="gradient-text">Account</span></h1>
                <p class="text-slate-500 font-medium">Begin your journey towards spiritual and personal excellence</p>
            </div>

            <form action="{{ route('register') }}" method="POST" id="regForm">
                @csrf

                <!-- Step 1: Role Selection -->
                <div id="step-1" class="step-content">
                    <p class="text-center text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-8">Choose your
                        journey</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                        <!-- Parent Role -->
                        <div onclick="selectRole('Parent')" id="role-parent"
                            class="role-card glass p-8 rounded-3xl text-center border-2 border-transparent hover:border-primary/30">
                            <div
                                class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-[#0B4D73] text-2xl mx-auto mb-6 transition-all group-hover:scale-110">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <h3 class="font-black uppercase tracking-widest text-xs mb-3 text-slate-900">Parent / Guardian
                            </h3>
                            <p class="text-[11px] text-slate-500 leading-relaxed font-bold">Manage your children's paths and
                                monitor progress.</p>
                        </div>

                        <!-- Participant Role -->
                        <div onclick="selectRole('Child')" id="role-participant"
                            class="role-card glass p-8 rounded-3xl text-center border-2 border-transparent hover:border-primary/30">
                            <div
                                class="w-16 h-16 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600 text-2xl mx-auto mb-6 transition-all group-hover:scale-110">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <h3 class="font-black uppercase tracking-widest text-xs mb-3 text-slate-900">Participant</h3>
                            <p class="text-[11px] text-slate-500 leading-relaxed font-bold">Young adult (7+) ready for
                                personal growth.</p>
                        </div>
                    </div>

                    <input type="hidden" name="role" id="selectedRole" required>

                    <div class="flex justify-center">
                        <button type="button" onclick="nextStep()" id="nextBtn" disabled
                            class="px-12 py-4 bg-slate-200 text-slate-400 font-black uppercase tracking-widest text-xs rounded-2xl cursor-not-allowed transition-all shadow-lg">
                            <span>Continue</span>
                            <i class="fas fa-arrow-right ml-2 text-[10px]"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Details -->
                <div id="step-2" class="step-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <input type="text" id="first_name" name="first_name" class="form-input" placeholder=" "
                                value="{{ old('first_name') }}" required>
                            <label for="first_name" class="form-label">First Name</label>
                            @error('first_name') <p
                                class="text-red-500 text-[10px] mt-2 font-black uppercase tracking-tight">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="text" id="last_name" name="last_name" class="form-input" placeholder=" "
                                value="{{ old('last_name') }}" required>
                            <label for="last_name" class="form-label">Last Name</label>
                            @error('last_name') <p
                                class="text-red-500 text-[10px] mt-2 font-black uppercase tracking-tight">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <input type="email" id="email" name="email" class="form-input" placeholder=" "
                            value="{{ old('email') }}" required>
                        <label for="email" class="form-label">Email Address</label>
                        @error('email') <p class="text-red-500 text-[10px] mt-2 font-black uppercase tracking-tight">
                        {{ $message }}</p> @enderror
                    </div>

                    <!-- Participant Specific Fields -->
                    <div id="participant-fields" class="hidden animate-fade-in">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <input type="date" id="date_of_birth" name="date_of_birth" class="form-input"
                                    placeholder=" ">
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                @error('date_of_birth') <p
                                    class="text-red-500 text-[10px] mt-2 font-black uppercase tracking-tight">{{ $message }}
                                </p> @enderror
                            </div>
                            <div class="form-group">
                                <select name="gender" class="form-input">
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                                <label class="form-label">Gender</label>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <input type="password" id="password" name="password" class="form-input" placeholder=" "
                                required>
                            <label for="password" class="form-label">Password</label>
                        </div>
                        <div class="form-group">
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="form-input" placeholder=" " required>
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 mt-10">
                        <button type="button" onclick="prevStep()"
                            class="flex-1 py-4 bg-slate-100 text-slate-500 font-black rounded-2xl hover:bg-slate-200 transition-all text-xs uppercase tracking-[0.15em] border border-slate-200">
                            Back
                        </button>
                        <button type="submit"
                            class="flex-[2] py-4 bg-gradient-to-r from-[#0B4D73] to-cyan-700 text-white font-black rounded-2xl hover:shadow-xl hover:shadow-blue-900/20 transition-all text-xs uppercase tracking-[0.2em] shadow-lg">
                            Complete Registration
                        </button>
                    </div>
                </div>
            </form>

            <p class="text-center text-slate-400 text-[10px] font-black uppercase tracking-widest mt-12">
                Already have an account? <a href="{{ route('login') }}" class="text-primary hover:underline ml-1">Sign
                    In</a>
            </p>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function selectRole(role) {
            document.getElementById('selectedRole').value = role;

            // UI Feedback
            document.querySelectorAll('.role-card').forEach(c => c.classList.remove('active'));
            document.getElementById('role-' + (role === 'Parent' ? 'parent' : 'participant')).classList.add('active');

            // Enable Next Button
            const nextBtn = document.getElementById('nextBtn');
            nextBtn.disabled = false;
            nextBtn.classList.remove('bg-slate-200', 'text-slate-400', 'cursor-not-allowed');
            nextBtn.classList.add('bg-gradient-to-r', 'from-[#0B4D73]', 'to-cyan-700', 'text-white', 'shadow-blue-900/20');

            // Toggle Participant Fields
            const pFields = document.getElementById('participant-fields');
            if (role === 'Child') {
                pFields.classList.remove('hidden');
                document.getElementById('date_of_birth').required = true;
            } else {
                pFields.classList.add('hidden');
                document.getElementById('date_of_birth').required = false;
            }
        }

        function nextStep() {
            document.getElementById('step-1').classList.add('hidden');
            document.getElementById('step-2').classList.remove('hidden');
        }

        function prevStep() {
            document.getElementById('step-2').classList.add('hidden');
            document.getElementById('step-1').classList.remove('hidden');
        }
    </script>
@endsection