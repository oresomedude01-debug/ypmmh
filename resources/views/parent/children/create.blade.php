@extends('layouts.dashboard')

@section('title', 'Add Child to Family')

@section('content')
    <div class="max-w-3xl mx-auto py-8">
        <div class="mb-8">
            <a href="{{ route('parent.dashboard') }}"
                class="text-slate-400 hover:text-primary transition-colors flex items-center gap-2 text-sm font-bold uppercase tracking-widest">
                <i class="fas fa-arrow-left"></i> Back to Family Overview
            </a>
        </div>

        <div class="glass rounded-[3rem] p-8 md:p-12 border border-white shadow-2xl">
            <div class="flex items-center gap-6 mb-12">
                <div class="w-16 h-16 rounded-2xl bg-blue-50 flex items-center justify-center text-[#0B4D73] text-2xl">
                    <i class="fas fa-child-reaching"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-black text-slate-900 leading-tight">Register a Child</h2>
                    <p class="text-slate-500 font-medium">Add a new family member to your mentoring hub.</p>
                </div>
            </div>

            <form action="{{ route('parent.children.store') }}" method="POST" class="space-y-8"
                enctype="multipart/form-data">
                @csrf

                <div>
                    <div class="flex items-center gap-6 mb-8">
                        <div class="shrink-0 relative group">
                            <div
                                class="w-24 h-24 rounded-2xl bg-slate-100 flex items-center justify-center overflow-hidden border-2 border-dashed border-slate-300 group-hover:border-[#0B4D73] transition-colors">
                                <img id="preview" class="w-full h-full object-cover hidden">
                                <div id="placeholder" class="text-center p-2">
                                    <i class="fas fa-camera text-slate-400 text-xl mb-1 group-hover:text-[#0B4D73]"></i>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase">Upload Photo</p>
                                </div>
                            </div>
                            <input type="file" name="profile_picture" onchange="previewImage(this)"
                                class="absolute inset-0 opacity-0 cursor-pointer">
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900">Profile Photo</h3>
                            <p class="text-xs text-slate-500">Upload a nice picture of your child.</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">First
                            Name</label>
                        <input type="text" name="first_name" required value="{{ old('first_name') }}"
                            class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-2 border-slate-100 focus:border-[#0B4D73] focus:outline-none transition-all font-medium">
                        @error('first_name') <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Last
                            Name</label>
                        <input type="text" name="last_name" required value="{{ old('last_name') }}"
                            class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-2 border-slate-100 focus:border-[#0B4D73] focus:outline-none transition-all font-medium">
                        @error('last_name') <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Date of
                            Birth</label>
                        <input type="date" name="date_of_birth" required value="{{ old('date_of_birth') }}"
                            class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-2 border-slate-100 focus:border-[#0B4D73] focus:outline-none transition-all font-medium">
                        @error('date_of_birth') <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Gender</label>
                        <select name="gender" required
                            class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-2 border-slate-100 focus:border-[#0B4D73] focus:outline-none transition-all font-medium">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('gender') <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Email
                        Address</label>
                    <input type="email" name="email" required value="{{ old('email') }}" placeholder="child@example.com"
                        class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-2 border-slate-100 focus:border-[#0B4D73] focus:outline-none transition-all font-medium">
                    <p class="text-[9px] text-slate-400 font-medium">Unique email for login.</p>
                    @error('email') <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Relationship</label>
                    <input type="text" name="relationship" value="{{ old('relationship', 'Parent') }}"
                        class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-2 border-slate-100 focus:border-[#0B4D73] focus:outline-none transition-all font-medium">
                    @error('relationship') <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="pt-8 flex justify-end">
                    <button type="submit"
                        class="px-10 py-4 bg-[#0B4D73] text-white rounded-2xl font-black uppercase tracking-widest text-[11px] shadow-2xl shadow-blue-900/20 hover:bg-slate-900 transition-all flex items-center gap-3 active:scale-95">
                        Register Family Member <i class="fas fa-plus text-[9px]"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview').src = e.target.result;
                    document.getElementById('preview').classList.remove('hidden');
                    document.getElementById('placeholder').classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection