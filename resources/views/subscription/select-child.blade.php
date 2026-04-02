@extends('layouts.dashboard')

@section('title', 'Select Mentee - ' . $program->name)

@section('content')
    <div class="max-w-2xl mx-auto py-12">
        <div class="text-center mb-10">
            <div
                class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-[#0B4D73] to-cyan-500 text-white shadow-xl mb-6">
                <i class="fas fa-child text-2xl"></i>
            </div>
            <h1 class="text-3xl font-black text-slate-900 mb-2">Who is this for?</h1>
            <p class="text-slate-500">Select which mentee you want to enroll in <span
                    class="font-bold text-[#0B4D73]">{{ $program->name }}</span></p>
        </div>

        <!-- Program Summary -->
        <div class="bg-gradient-to-r from-[#0B4D73] to-cyan-600 rounded-2xl p-6 mb-8 text-white flex items-center gap-4">
            <div class="w-16 h-16 rounded-xl bg-white/20 flex items-center justify-center">
                @if($program->thumbnail_path)
                    <img src="{{ asset('storage/' . $program->thumbnail_path) }}" class="w-full h-full object-cover rounded-xl">
                @else
                    <i class="fas fa-book-open text-2xl"></i>
                @endif
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-lg">{{ $program->name }}</h3>
                <p class="text-white/70 text-sm">{{ Str::limit($program->description, 60) }}</p>
            </div>
            <div class="text-right">
                <div class="text-2xl font-black">₦{{ number_format($program->price) }}</div>
                <div class="text-xs text-white/70 uppercase">One-time</div>
            </div>
        </div>

        <!-- Children Selection -->
        <div class="space-y-4">
            @foreach($children as $child)
                <form action="{{ route('subscription.select-child') }}" method="POST">
                    @csrf
                    <input type="hidden" name="program_id" value="{{ $program->id }}">
                    <input type="hidden" name="child_id" value="{{ $child->id }}">

                    <button type="submit"
                        class="w-full p-6 bg-white rounded-2xl border-2 border-slate-100 hover:border-[#0B4D73] hover:shadow-lg transition-all group flex items-center gap-4 text-left">
                        <div class="w-14 h-14 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0">
                            @if($child->profile_picture)
                                <img src="{{ asset('storage/' . $child->profile_picture) }}" class="w-full h-full object-cover">
                            @else
                                <div
                                    class="w-full h-full bg-gradient-to-br from-blue-100 to-cyan-100 flex items-center justify-center text-[#0B4D73] font-bold text-lg">
                                    {{ substr($child->first_name, 0, 1) }}{{ substr($child->last_name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-slate-900 group-hover:text-[#0B4D73] transition-colors">
                                {{ $child->full_name }}</h4>
                            <p class="text-sm text-slate-400">
                                @if($child->date_of_birth)
                                    {{ \Carbon\Carbon::parse($child->date_of_birth)->age }} years old
                                @else
                                    Age not set
                                @endif
                                @if($child->unique_number)
                                    <span class="mx-2">•</span>
                                    <span class="font-mono">{{ $child->unique_number }}</span>
                                @endif
                            </p>
                        </div>
                        <div class="text-slate-300 group-hover:text-[#0B4D73] transition-colors">
                            <i class="fas fa-chevron-right text-lg"></i>
                        </div>
                    </button>
                </form>
            @endforeach
        </div>

        <!-- Cancel Link -->
        <div class="mt-8 text-center">
            <a href="{{ route('programs.explore') }}" class="text-slate-400 hover:text-slate-600 font-medium text-sm">
                <i class="fas fa-arrow-left mr-1"></i> Cancel and go back
            </a>
        </div>
    </div>
@endsection