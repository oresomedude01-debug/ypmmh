@extends('layouts.dashboard')

@section('title', 'Manage Mentor: ' . $mentor->first_name)

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('admin.mentors.index') }}" class="text-[#0B4D73] hover:underline text-sm font-medium">
                        <i class="fas fa-arrow-left"></i> Back to Mentors
                    </a>
                </div>
                <h1 class="text-2xl font-bold text-slate-900">Mentor Profile: {{ $mentor->first_name }}
                    {{ $mentor->last_name }}
                </h1>
                <p class="text-slate-600 text-sm">Manage assignments and view progress for this mentor.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.users.edit', $mentor->id) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-50 transition-colors shadow-sm">
                    <i class="fas fa-edit text-xs"></i>
                    <span>Edit Profile</span>
                </a>
            </div>
        </div>

        <!-- Quick Stats & Info -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Mentor Info Card -->
            <div class="glass rounded-2xl p-6 space-y-6">
                <div class="flex flex-col items-center text-center">
                    @if($mentor->profile_picture)
                        <img src="{{ asset('storage/' . $mentor->profile_picture) }}" alt=""
                            class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-md mb-4">
                    @else
                        <div
                            class="w-24 h-24 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-3xl font-bold border-4 border-white shadow-md mb-4 uppercase">
                            {{ substr($mentor->first_name, 0, 1) }}{{ substr($mentor->last_name, 0, 1) }}
                        </div>
                    @endif
                    <h3 class="text-xl font-bold text-slate-900">{{ $mentor->first_name }} {{ $mentor->last_name }}</h3>
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 mt-1">
                        Verified Mentor
                    </span>
                </div>

                <div class="space-y-4 pt-4 border-t border-slate-100">
                    <div class="flex items-center gap-3 text-sm">
                        <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <span class="text-slate-600 font-medium">{{ $mentor->email }}</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                            <i class="fas fa-phone"></i>
                        </div>
                        <span class="text-slate-600 font-medium">{{ $mentor->phone_number ?? 'Not provided' }}</span>
                    </div>
                </div>
            </div>

            <!-- Assignment Stats -->
            <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="glass rounded-2xl p-6 flex items-center gap-4">
                    <div
                        class="w-14 h-14 rounded-2xl bg-[#0B4D73]/5 text-[#0B4D73] flex items-center justify-center text-2xl shadow-sm">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Assigned Programs</p>
                        <h3 class="text-2xl font-bold text-slate-900">{{ $programs->count() }}</h3>
                    </div>
                </div>
                <div class="glass rounded-2xl p-6 flex items-center gap-4">
                    <div
                        class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl shadow-sm">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Students</p>
                        <h3 class="text-2xl font-bold text-slate-900">{{ $children->count() }}</h3>
                    </div>
                </div>
                <div class="glass rounded-2xl p-6 flex items-center gap-4">
                    <div
                        class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl shadow-sm">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Classes This Week</p>
                        <h3 class="text-2xl font-bold text-slate-900">0</h3>
                    </div>
                </div>
                <div class="glass rounded-2xl p-6 flex items-center gap-4">
                    <div
                        class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-2xl shadow-sm">
                        <i class="fas fa-star"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Performance Rank</p>
                        <h3 class="text-2xl font-bold text-slate-900">N/A</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assignments Section -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <!-- Programs Management -->
            <div class="glass rounded-2xl overflow-hidden flex flex-col h-full shadow-sm">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div>
                        <h3 class="font-bold text-slate-900">Managed Programs</h3>
                        <p class="text-xs text-slate-500">Curriculums where this mentor is lead.</p>
                    </div>
                    <button onclick="document.getElementById('assignProgramModal').classList.remove('hidden')"
                        class="p-2 bg-[#0B4D73] text-white rounded-lg hover:bg-[#093e5d] transition-all shadow-md">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <div class="p-4 flex-grow overflow-y-auto max-h-[400px]">
                    @forelse($programs as $p)
                        <div
                            class="flex items-center justify-between p-4 border border-slate-50 hover:border-blue-100 hover:bg-blue-50/20 transition-all rounded-2xl group mb-3 last:mb-0">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-xl bg-white border border-slate-100 text-[#0B4D73] flex items-center justify-center font-bold shadow-sm">
                                    {{ substr($p->name, 0, 1) }}
                                </div>
                                <div>
                                    <p
                                        class="font-bold text-slate-900 text-sm group-hover:text-[#0B4D73] transition-colors leading-tight">
                                        {{ $p->name }}
                                    </p>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] text-slate-400 capitalize">{{ $p->type }}</span>
                                        <span class="text-[10px] text-slate-300">|</span>
                                        <span class="text-[10px] text-slate-400">{{ $p->children->count() }} Students</span>
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('admin.mentors.unassign-program', [$mentor->id, $p->id]) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to unassign this program?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-slate-300 hover:text-red-500 p-2 transition-colors"
                                    title="Unassign">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                            <i class="fas fa-book text-4xl mb-3 opacity-20"></i>
                            <p class="text-sm italic">No programs assigned yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Supervised Children -->
            <div class="glass rounded-2xl overflow-hidden flex flex-col h-full shadow-sm">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div>
                        <h3 class="font-bold text-slate-900">Student Supervision</h3>
                        <p class="text-xs text-slate-500">Children enrolled in this mentor's programs.</p>
                    </div>
                    <div class="text-[10px] font-bold text-[#0B4D73] bg-blue-50 px-2 py-1 rounded-lg">
                        Total: {{ $children->count() }}
                    </div>
                </div>
                <div class="p-4 flex-grow overflow-y-auto max-h-[400px]">
                    @forelse($children as $child)
                        <div
                            class="flex items-center justify-between p-3 border-b border-slate-50 last:border-0 hover:bg-slate-50/50 transition-all rounded-xl group relative">
                            <div class="flex items-center gap-3">
                                @if($child->profile_picture)
                                    <img src="{{ asset('storage/' . $child->profile_picture) }}" alt=""
                                        class="w-10 h-10 rounded-full object-cover">
                                @else
                                    <div
                                        class="w-10 h-10 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-[10px] uppercase">
                                        {{ substr($child->first_name, 0, 1) }}{{ substr($child->last_name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="font-bold text-slate-900 text-sm leading-tight">{{ $child->first_name }}
                                        {{ $child->last_name }}</p>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-mono text-slate-400">{{ $child->unique_number }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.children.show', $child->id) }}"
                                    class="text-[#0B4D73] hover:underline text-[10px] font-bold">View Journey</a>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                            <i class="fas fa-child text-4xl mb-3 opacity-20"></i>
                            <p class="text-sm italic">No students under supervision.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Assign Program Modal -->
    <div id="assignProgramModal"
        class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="font-bold text-slate-900">Assign Program</h3>
                <button onclick="document.getElementById('assignProgramModal').classList.add('hidden')"
                    class="text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('admin.mentors.assign-program', $mentor->id) }}" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PATCH')
                <div>
                    <label class="text-sm font-bold text-slate-700 block mb-2">Select Program</label>
                    <select name="program_id" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] transition-all cursor-pointer text-sm">
                        <option value="">-- Select a program --</option>
                        @foreach($allPrograms as $p)
                            @if($p->mentor_id != $mentor->id)
                                <option value="{{ $p->id }}">{{ $p->name }} ({{ ucfirst($p->type) }})</option>
                            @endif
                        @endforeach
                    </select>
                    <p class="text-[10px] text-slate-500 mt-3 italic leading-relaxed">
                        <i class="fas fa-info-circle mr-1"></i> Selection will set this user as the primary mentor for the
                        program.
                    </p>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('assignProgramModal').classList.add('hidden')"
                        class="flex-1 py-3 border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-all text-xs">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-1 py-3 bg-[#0B4D73] text-white font-bold rounded-xl hover:bg-[#093e5d] transition-all shadow-lg shadow-blue-900/10 text-xs text-center">
                        Assign Now
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection