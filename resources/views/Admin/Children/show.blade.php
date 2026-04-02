@extends('layouts.dashboard')

@section('title', 'Mentee Profile: ' . $child->first_name)

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('admin.children.index') }}"
                        class="text-[#0B4D73] hover:underline text-sm font-medium">
                        <i class="fas fa-arrow-left"></i> Back to Mentees
                    </a>
                </div>
                <h1 class="text-2xl font-bold text-slate-900">Mentee Profile: {{ $child->first_name }}
                    {{ $child->last_name }}</h1>
                <p class="text-slate-600 text-sm">Manage educational journey and program enrollments.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.users.edit', $child->id) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-50 transition-colors shadow-sm">
                    <i class="fas fa-edit text-xs"></i>
                    <span>Edit Profile</span>
                </a>
            </div>
        </div>

        <!-- Quick Stats & Student Info -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Student Identity Card -->
            <div class="glass rounded-2xl p-6 space-y-6">
                <div class="flex flex-col items-center text-center">
                    @if($child->profile_picture)
                        <img src="{{ asset('storage/' . $child->profile_picture) }}" alt=""
                            class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-md mb-4">
                    @else
                        <div
                            class="w-24 h-24 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center text-3xl font-bold border-4 border-white shadow-md mb-4 uppercase">
                            {{ substr($child->first_name, 0, 1) }}{{ substr($child->last_name, 0, 1) }}
                        </div>
                    @endif
                    <h3 class="text-xl font-bold text-slate-900">{{ $child->first_name }} {{ $child->last_name }}</h3>
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 mt-1 border border-amber-100">
                        ID: {{ $child->unique_number ?? 'PENDING' }}
                    </span>
                </div>

                <div class="space-y-4 pt-4 border-t border-slate-100">
                    <div class="flex items-center gap-4 text-sm">
                        <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-slate-400">Email Address</p>
                            <p class="text-slate-600 font-medium">{{ $child->email }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 text-sm">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                            <i class="fas fa-user-friends"></i>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-slate-400">Parent / Guardian</p>
                            @if($child->parent)
                                <p class="text-slate-900 font-bold">{{ $child->parent->first_name }}
                                    {{ $child->parent->last_name }}</p>
                            @else
                                <p class="text-slate-400 italic">No parent linked</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-4 text-sm">
                        <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                            <i class="fas fa-birthday-cake"></i>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-slate-400">Age / DOB</p>
                            <p class="text-slate-600 font-medium">
                                {{ $child->date_of_birth ? \Carbon\Carbon::parse($child->date_of_birth)->format('M d, Y') : 'N/A' }}
                                @if($child->date_of_birth)
                                    ({{ \Carbon\Carbon::parse($child->date_of_birth)->age }} yrs)
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Progress Summary -->
            <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="glass rounded-2xl p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-blue-50 text-[#0B4D73] flex items-center justify-center text-xl">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-lg">Level 1</span>
                        </div>
                        <h4 class="text-lg font-bold text-slate-900 mb-1">Overall Curriculum Progress</h4>
                        <p class="text-xs text-slate-500 mb-6">Percentage of completed materials across all programs.</p>
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-xs font-bold">
                            <span class="text-slate-600">Completion rate</span>
                            <span class="text-[#0B4D73]">0%</span>
                        </div>
                        <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-[#0B4D73] to-blue-500 rounded-full transition-all duration-1000"
                                style="width: 0%"></div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="glass rounded-2xl p-5 border border-slate-100/50">
                        <div
                            class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-lg mb-3">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Active Programs</p>
                        <h3 class="text-xl font-bold text-slate-900">{{ $enrollments->where('status', 'active')->count() }}
                        </h3>
                    </div>
                    <div class="glass rounded-2xl p-5 border border-slate-100/50">
                        <div
                            class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg mb-3">
                            <i class="fas fa-award"></i>
                        </div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Earned Credits</p>
                        <h3 class="text-xl font-bold text-slate-900">0</h3>
                    </div>
                    <div class="glass rounded-2xl p-5 border border-slate-100/50">
                        <div
                            class="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-lg mb-3">
                            <i class="fas fa-sticky-note"></i>
                        </div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Reports</p>
                        <h3 class="text-xl font-bold text-slate-900">0</h3>
                    </div>
                    <div class="glass rounded-2xl p-5 border border-slate-100/50">
                        <div
                            class="w-10 h-10 rounded-lg bg-pink-50 text-pink-600 flex items-center justify-center text-lg mb-3">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Attendance</p>
                        <h3 class="text-xl font-bold text-slate-900">0%</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enrollment Management Section -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Enrolled Programs List -->
            <div class="xl:col-span-2 glass rounded-3xl overflow-hidden shadow-sm flex flex-col min-h-[400px]">
                <div class="p-6 border-b border-slate-100 bg-slate-50/30 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#0B4D73]/10 text-[#0B4D73] flex items-center justify-center">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900">Learning Journey</h3>
                            <p class="text-xs text-slate-500">Current and past program enrollments.</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 bg-white border border-slate-200 rounded-full text-xs font-bold text-[#0B4D73]">
                        {{ $enrollments->count() }} Total
                    </span>
                </div>

                <div class="p-6 flex-grow">
                    @if($enrollments->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($enrollments as $enrollment)
                                <div
                                    class="p-4 rounded-2xl border border-slate-100 hover:border-blue-200 hover:bg-blue-50/30 transition-all group relative overflow-hidden">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-lg bg-white border border-slate-100 text-[#0B4D73] flex items-center justify-center font-bold">
                                                {{ substr($enrollment->program->name ?? 'P', 0, 1) }}
                                            </div>
                                            <div>
                                                <h4
                                                    class="font-bold text-slate-900 text-sm group-hover:text-[#0B4D73] transition-colors">
                                                    {{ $enrollment->program->name ?? 'Unknown Program' }}
                                                </h4>
                                                @if($enrollment->program)
                                                    <p class="text-[10px] text-slate-400 capitalize">{{ $enrollment->program->type }}
                                                    Program</p>
                                                @else
                                                    <p class="text-[10px] text-red-400 italic">Program data missing</p>
                                                @endif
                                            </div>
                                        </div>
                                        <span
                                            class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $enrollment->status == 'active' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-slate-50 text-slate-400 border border-slate-100' }}">
                                            {{ ucfirst($enrollment->status) }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between pt-4 border-t border-slate-100/50">
                                        <span class="text-[10px] text-slate-500">Enrolled:
                                            {{ $enrollment->created_at->format('M d, Y') }}</span>
                                        <div class="flex items-center gap-2">
                                            @if($enrollment->program)
                                                <span class="text-[10px] font-bold text-slate-400">
                                                    @if($enrollment->program->type == 'scheduled')
                                                        {{ \Carbon\Carbon::parse($enrollment->program->start_date)->format('M Y') }}
                                                        -
                                                        {{ \Carbon\Carbon::parse($enrollment->program->end_date)->format('M Y') }}
                                                    @else
                                                        Age {{ $enrollment->program->age_min }} -
                                                        {{ $enrollment->program->age_max }}
                                                    @endif
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center h-full py-12 text-slate-400">
                            <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mb-4">
                                <i class="fas fa-folder-open text-2xl opacity-20"></i>
                            </div>
                            <p class="text-sm font-medium italic">Not enrolled in any programs yet.</p>
                            <p class="text-xs text-slate-300 mt-1">Enroll the mentee using the quick panel on the right.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Enrollment Action Panel -->
            <div
                class="xl:col-span-1 glass rounded-3xl overflow-hidden shadow-sm flex flex-col h-full bg-gradient-to-br from-white to-slate-50">
                <div class="p-6 border-b border-slate-100 bg-[#0B4D73]/5">
                    <h3 class="font-bold text-slate-900">Enrollment Center</h3>
                    <p class="text-xs text-slate-500">Quickly enroll in new curriculum programmes.</p>
                </div>

                <div class="p-6">
                    <form action="{{ route('admin.children.enroll', $child->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label
                                class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Available Scheduled
                                Programmes</label>
                            <select name="program_id" required
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] transition-all cursor-pointer text-sm font-medium">
                                <option value="">-- Choose a programme --</option>
                                @foreach($availablePrograms as $program)
                                    <option value="{{ $program->id }}">
                                        {{ $program->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-slate-400 mt-3 leading-relaxed">
                                <i class="fas fa-info-circle mr-1 text-[#0B4D73]"></i>
                                Only scheduled programmes are available for manual enrollment. Rolling programmes are assigned automatically based on age.
                            </p>
                        </div>

                        <button type="submit"
                            class="w-full py-3.5 bg-[#0B4D73] text-white rounded-xl font-bold text-sm hover:bg-[#093e5d] transition-all shadow-lg shadow-blue-900/10 flex items-center justify-center gap-2">
                            <i class="fas fa-user-plus text-xs"></i>
                            <span>Start Enrollment</span>
                        </button>

                        @if($availablePrograms->count() == 0)
                            <div class="p-4 bg-amber-50 border border-amber-100 rounded-xl mt-4">
                                <p class="text-[11px] text-amber-700 font-medium text-center">
                                    All available programmes have been filled or the mentee is already enrolled.
                                </p>
                            </div>
                        @endif
                    </form>

                    <div class="mt-8 pt-8 border-t border-slate-100">
                        <h4 class="text-xs font-bold text-slate-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-lightbulb text-amber-400"></i>
                            Management Insight
                        </h4>
                        <ul class="space-y-3">
                            <li class="flex items-start gap-3">
                                <div class="w-1.5 h-1.5 rounded-full bg-[#0B4D73] mt-1"></div>
                                <p class="text-[11px] text-slate-600 leading-tight">Enrollments are activated immediately.
                                </p>
                            </li>
                            <li class="flex items-start gap-3">
                                <div class="w-1.5 h-1.5 rounded-full bg-[#0B4D73] mt-1"></div>
                                <p class="text-[11px] text-slate-600 leading-tight">Mentors assigned to the selected
                                    programme will gain supervision access.</p>
                            </li>
                            <li class="flex items-start gap-3">
                                <div class="w-1.5 h-1.5 rounded-full bg-[#0B4D73] mt-1"></div>
                                <p class="text-[11px] text-slate-600 leading-tight">The mentee's unique ID is required for
                                    lesson tracking.</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection