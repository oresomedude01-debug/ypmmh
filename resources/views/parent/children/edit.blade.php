@extends('layouts.dashboard')

@section('title', 'View ' . $child->first_name . '\'s Profile')

@section('content')
    <div class="max-w-3xl mx-auto py-8">
        <div class="mb-8">
            <a href="{{ route('parent.children.show', $child) }}"
                class="text-slate-400 hover:text-primary transition-colors flex items-center gap-2 text-sm font-bold uppercase tracking-widest">
                <i class="fas fa-arrow-left"></i> Back to {{ $child->first_name }}'s Overview
            </a>
        </div>

        {{-- Success/Error Messages --}}
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-2xl flex items-center gap-3 text-green-700">
                <i class="fas fa-check-circle"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="glass rounded-[3rem] p-8 md:p-12 border border-white shadow-2xl">
            <div class="flex items-center gap-6 mb-12">
                {{-- Child Avatar --}}
                <div class="relative">
                    <div class="w-20 h-20 rounded-2xl overflow-hidden border-4 border-white shadow-xl">
                        @if($child->profile_picture)
                            <img src="{{ asset('storage/' . $child->profile_picture) }}" class="w-full h-full object-cover">
                        @else
                            <div
                                class="w-full h-full bg-blue-50 flex items-center justify-center text-2xl font-black text-[#0B4D73]">
                                {{ substr($child->first_name, 0, 1) }}{{ substr($child->last_name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                </div>
                <div>
                    <h2 class="text-3xl font-black text-slate-900 leading-tight">{{ $child->first_name }}'s Profile</h2>
                    <p class="text-slate-500 font-medium">Personal information is protected. Request updates below.</p>
                </div>
            </div>

            <div class="space-y-8 opacity-75 pointer-events-none select-none grayscale-[0.5]">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">First
                            Name</label>
                        <div
                            class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-2 border-slate-100 font-bold text-slate-700">
                            {{ $child->first_name }}
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Last
                            Name</label>
                        <div
                            class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-2 border-slate-100 font-bold text-slate-700">
                            {{ $child->last_name }}
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Date of
                            Birth</label>
                        <div
                            class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-2 border-slate-100 font-bold text-slate-700">
                            {{ $child->date_of_birth ? $child->date_of_birth->format('F d, Y') : 'N/A' }}
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Gender</label>
                        <div
                            class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-2 border-slate-100 font-bold text-slate-700 capitalize">
                            {{ $child->gender }}
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Email</label>
                    <div
                        class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-2 border-slate-100 font-bold text-slate-700">
                        {{ $child->email }}
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-slate-100">
                <div class="flex items-start gap-4 p-4 bg-amber-50 rounded-2xl text-amber-900 border border-amber-100 mb-6">
                    <i class="fas fa-info-circle mt-1"></i>
                    <p class="text-sm">For security reasons, profile information can only be updated by administrators. If
                        you need to make corrections, please describe them below.</p>
                </div>

                <button onclick="document.getElementById('reportForm').classList.toggle('hidden')"
                    class="w-full py-4 bg-white border-2 border-slate-200 text-slate-700 rounded-2xl font-bold hover:bg-slate-50 transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-flag"></i> Request Profile Update / Report Issue
                </button>

                <form id="reportForm" action="{{ route('parent.children.report', $child) }}" method="POST"
                    class="hidden mt-6 animate-fade-in relative z-10">
                    @csrf
                    <div class="space-y-4">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">What needs to be
                            changed?</label>
                        <textarea name="issue" rows="4" required
                            placeholder="e.g. Please correct the spelling of the first name to..."
                            class="w-full px-6 py-4 rounded-2xl bg-white border-2 border-slate-200 focus:border-[#0B4D73] focus:outline-none transition-all font-medium"></textarea>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="px-8 py-3 bg-[#0B4D73] text-white rounded-xl font-bold text-sm shadow-lg hover:bg-slate-900 transition-all">
                                Submit Request
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection