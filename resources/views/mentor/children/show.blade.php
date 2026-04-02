@extends('layouts.dashboard')

@section('title', 'Student Profile: ' . $child->first_name)

@section('content')
    <div class="space-y-6 animate-fade-in">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ url()->previous() }}"
                        class="text-[#0B4D73] hover:underline text-sm font-medium transition-colors">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
                <h1 class="text-3xl font-bold text-slate-900">Student Profile: {{ $child->full_name }}</h1>
                <p class="text-slate-600 text-sm">Review student progress and record educational observations.</p>
            </div>
            @if(isset($program))
                <div class="flex items-center gap-2">
                    <span class="px-4 py-2 bg-blue-50 text-[#0B4D73] rounded-xl text-sm font-bold border border-blue-100">
                        <i class="fas fa-book-reader mr-2"></i> {{ $program->name }}
                    </span>
                </div>
            @endif
        </div>

        <!-- Quick Stats & Student Info -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Student Identity Card -->
            <div class="lg:col-span-1 space-y-6">
                <div class="glass rounded-2xl p-6 shadow-sm border border-slate-100/50">
                    <div class="flex flex-col items-center text-center">
                        <div class="relative group">
                            @if($child->profile_picture)
                                <img src="{{ asset('storage/' . $child->profile_picture) }}" alt=""
                                    class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg mb-4">
                            @else
                                <div
                                    class="w-32 h-32 rounded-full bg-gradient-to-br from-indigo-50 to-blue-100 text-[#0B4D73] flex items-center justify-center text-4xl font-bold border-4 border-white shadow-lg mb-4 uppercase">
                                    {{ substr($child->first_name, 0, 1) }}{{ substr($child->last_name, 0, 1) }}
                                </div>
                            @endif
                            <div class="absolute bottom-4 right-2 w-6 h-6 bg-emerald-500 border-2 border-white rounded-full"
                                title="Active Student"></div>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 leading-tight">{{ $child->full_name }}</h3>
                        <p class="text-slate-500 text-sm font-medium mb-3">Student</p>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-[#0B4D73]/10 text-[#0B4D73] border border-[#0B4D73]/20 uppercase tracking-widest">
                            ID: {{ $child->unique_number ?? 'PENDING' }}
                        </span>
                    </div>

                    <div class="space-y-4 pt-6 mt-6 border-t border-slate-100">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400">
                                <i class="fas fa-birthday-cake text-lg"></i>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Age / DOB</p>
                                <p class="text-slate-700 font-bold">
                                    {{ $child->age ?? 'N/A' }} Years
                                    <span class="text-[10px] text-slate-400 block font-medium">
                                        {{ $child->date_of_birth ? \Carbon\Carbon::parse($child->date_of_birth)->format('M d, Y') : 'Unknown' }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                                <i class="fas fa-user-friends text-lg"></i>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Parent / Guardian
                                </p>
                                @if($child->parent)
                                    <p class="text-slate-700 font-bold">{{ $child->parent->full_name }}</p>
                                @else
                                    <p class="text-slate-400 italic font-medium">Not Linked</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shared Programs -->
                <div class="glass rounded-2xl p-6 shadow-sm border border-slate-100/50">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Your Shared Programs</h4>
                    <div class="space-y-3">
                        @foreach($child->programs as $sharedProgram)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="text-sm font-bold text-slate-700 truncate mr-2">{{ $sharedProgram->name }}</span>
                                <span
                                    class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-white text-[#0B4D73] border border-slate-200">
                                    {{ ucfirst($sharedProgram->type) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Main Profile Content -->
            <div class="lg:col-span-3 space-y-6">
                <!-- Learning Status -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="glass rounded-2xl p-6 border border-slate-100/50 flex items-center gap-4">
                        <div
                            class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl shadow-sm">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Enrollment</p>
                            <h3 class="text-xl font-bold text-slate-900">{{ $child->programs->count() }} Programs</h3>
                        </div>
                    </div>
                    <div class="glass rounded-2xl p-6 border border-slate-100/50 flex items-center gap-4">
                        <div
                            class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl shadow-sm">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Progress</p>
                            <h3 class="text-xl font-bold text-slate-900">0% Average</h3>
                        </div>
                    </div>
                    <div class="glass rounded-2xl p-6 border border-slate-100/50 flex items-center gap-4">
                        <div
                            class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl shadow-sm">
                            <i class="fas fa-sticky-note"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Notes</p>
                            <h3 class="text-xl font-bold text-slate-900">{{ $child->observations->count() }} Recorded</h3>
                        </div>
                    </div>
                </div>

                <!-- Observations Section -->
                <div
                    class="glass rounded-3xl overflow-hidden shadow-sm border border-slate-100/50 flex flex-col min-h-[500px]">
                    <div class="p-6 border-b border-slate-100 bg-slate-50/30 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                <i class="fas fa-clipboard-check"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900">Educational Observations</h3>
                                <p class="text-xs text-slate-500">Track student development and learning outcomes.</p>
                            </div>
                        </div>
                        <button onclick="document.getElementById('addObservationModal').classList.remove('hidden')"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-[#0B4D73] text-white rounded-xl text-xs font-bold hover:bg-[#093e5d] transition-all shadow-md shadow-blue-900/10">
                            <i class="fas fa-plus"></i>
                            <span>New Observation</span>
                        </button>
                    </div>

                    <div class="p-6 flex-grow">
                        @if($child->observations->count() > 0)
                            <div class="space-y-6">
                                @foreach($child->observations as $observation)
                                    <div class="flex gap-4 group">
                                        <div class="flex-shrink-0 flex flex-col items-center">
                                            <div class="w-10 h-10 rounded-full bg-indigo-50 text-[#0B4D73] flex items-center justify-center font-bold border-2 border-white shadow-sm">
                                                <i class="fas fa-user-edit text-xs"></i>
                                            </div>
                                            <div class="w-px h-full bg-slate-100 group-last:hidden mt-2"></div>
                                        </div>
                                        <div class="flex-1 pb-8 group-last:pb-0">
                                            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                                                <div class="flex items-center justify-between mb-3">
                                                    <span class="text-xs font-bold text-[#0B4D73] bg-blue-50 px-2 py-1 rounded-lg">Your Entry</span>
                                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                                        {{ $observation->created_at->format('M d, Y \a\t H:i') }}
                                                    </span>
                                                </div>
                                                <p class="text-slate-600 text-sm leading-relaxed whitespace-pre-line">{{ $observation->content }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="h-full flex flex-col items-center justify-center text-slate-400 py-12">
                                <div class="w-20 h-20 rounded-full bg-white flex items-center justify-center mb-6 shadow-sm border border-slate-100">
                                    <i class="fas fa-comments text-3xl opacity-20"></i>
                                </div>
                                <h4 class="text-lg font-bold text-slate-900 mb-2">No Observations Yet</h4>
                                <p class="text-sm italic max-w-xs text-center">Start tracking {{ $child->first_name }}'s journey by adding your first educational observation.</p>
                                <button onclick="document.getElementById('addObservationModal').classList.remove('hidden')"
                                        class="mt-8 px-6 py-2 bg-indigo-50 text-indigo-600 font-bold rounded-xl text-xs border border-indigo-100 hover:bg-indigo-100 transition-colors">
                                    Add First Entry
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Observation Modal -->
    <div id="addObservationModal"
        class="hidden fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[9999] flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full transform transition-all scale-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-indigo-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                        <i class="fas fa-pen-nib"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">Record Observation</h3>
                </div>
                <button onclick="document.getElementById('addObservationModal').classList.add('hidden')"
                    class="text-slate-400 hover:text-red-500 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form action="{{ route('mentor.observations.store', $child->id) }}" method="POST" class="p-8 space-y-6">
                @csrf
                <div class="space-y-4">
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Observations help parents understand their child's progress. Please be descriptive and constructive.
                    </p>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 flex items-center gap-2">
                            <span>Observation Content</span>
                            <span
                                class="text-[10px] text-red-500 bg-red-50 px-1.5 py-0.5 rounded uppercase font-extrabold tracking-tighter">Required</span>
                        </label>
                        <textarea name="observation" rows="6" required minlength="10"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] transition-all resize-none"
                            placeholder="Describe what was observed today..."></textarea>
                    </div>
                </div>

                <div class="flex gap-4 mt-8">
                    <button type="button" onclick="document.getElementById('addObservationModal').classList.add('hidden')"
                        class="flex-1 px-4 py-3 border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-colors">
                        Discard
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-3 bg-[#0B4D73] text-white font-bold rounded-xl hover:bg-[#093e5d] transition-all shadow-lg shadow-blue-900/20">
                        Post Entry
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection