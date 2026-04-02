@extends('layouts.dashboard')

@section('title', 'Family Observations')

@section('content')
    <div class="max-w-5xl mx-auto space-y-8 animate-fade-in pb-10">
        <!-- Header -->
        <div
            class="bg-gradient-to-br from-[#0B4D73] to-[#04334d] rounded-[2rem] p-8 md:p-10 text-white shadow-xl flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <h1 class="text-3xl font-black mb-2 flex items-center gap-3">
                    <i class="fas fa-glasses shadow-lg"></i>
                    Guardian Watch
                </h1>
                <p class="text-blue-100 font-medium opacity-80">Mentor observations and behavioral insights for all
                    children.</p>
            </div>
            <div class="hidden md:block">
                <span
                    class="px-6 py-3 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 text-xs font-black uppercase tracking-widest">
                    {{ $observations->total() }} Total Insights
                </span>
            </div>
        </div>

        <!-- Observations List -->
        <div class="space-y-6">
            @forelse($observations as $obs)
                <div
                    class="bg-white rounded-3xl p-6 md:p-8 border border-slate-100 shadow-sm hover:shadow-md transition-all relative group">
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Target Child -->
                        <div
                            class="md:w-32 flex flex-col items-center justify-center bg-slate-50 rounded-2xl p-4 shrink-0 transition-colors group-hover:bg-[#0B4D73]/5">
                            <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-white shadow-sm mb-2">
                                @if($obs->child->profile_picture)
                                    <img src="{{ asset('storage/' . $obs->child->profile_picture) }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div
                                        class="w-full h-full bg-[#0B4D73] flex items-center justify-center text-xs font-black text-white">
                                        {{ substr($obs->child->first_name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <span class="text-xs font-black text-slate-900">{{ $obs->child->first_name }}</span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Student</span>
                        </div>

                        <!-- Observation Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                                    <span class="text-[10px] font-black text-[#0B4D73] uppercase tracking-widest">New
                                        Assessment</span>
                                </div>
                                <span
                                    class="text-[10px] font-bold text-slate-400">{{ $obs->created_at->format('M d, Y • H:i') }}</span>
                            </div>
                            <p class="text-sm text-slate-600 leading-relaxed font-medium italic mb-6">
                                "{{ $obs->content }}"
                            </p>

                            <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center text-[10px] font-black text-[#0B4D73]">
                                        {{ substr($obs->mentor->first_name, 0, 1) }}
                                    </div>
                                    <p class="text-[10px] font-bold text-slate-700">Guide:
                                        <strong>{{ $obs->mentor->full_name }}</strong></p>
                                </div>
                                <a href="{{ route('parent.children.show', $obs->child_id) }}"
                                    class="px-4 py-2 bg-slate-50 text-slate-600 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-[#0B4D73] hover:text-white transition-all">
                                    View History
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-[2.5rem] p-20 text-center border-2 border-dashed border-slate-200">
                    <i class="fas fa-search text-5xl text-slate-200 mb-4 block"></i>
                    <h3 class="text-xl font-black text-slate-400">Listening to the Circles...</h3>
                    <p class="text-slate-500 text-xs">When our guides record observations for your children, they will appear
                        here instantly.</p>
                </div>
            @endforelse

            <!-- Pagination -->
            <div class="pt-4">
                {{ $observations->links() }}
            </div>
        </div>
    </div>
@endsection