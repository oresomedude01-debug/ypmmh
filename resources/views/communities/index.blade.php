@extends('layouts.dashboard')

@section('title', 'Community Hub')

@section('content')
    <div class="space-y-8 animate-fade-in">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-[#0B4D73] to-blue-600 bg-clip-text text-transparent">
                    Community Hub
                </h1>
                <p class="text-slate-600">Access and moderate your program communities.</p>
            </div>

            @if(auth()->user()->hasRole('Admin'))
                <form action="{{ route('admin.communities.index') }}" method="GET" class="relative w-full md:w-72">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] transition-all shadow-sm"
                        placeholder="Filter communities...">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                </form>
            @endif
        </div>

        <!-- Active Communities List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($programs as $program)
                <div
                    class="glass rounded-3xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div
                                class="w-12 h-12 rounded-2xl bg-indigo-50 text-[#0B4D73] flex items-center justify-center text-2xl shadow-inner group-hover:scale-110 transition-transform">
                                <i class="fas fa-comments"></i>
                            </div>
                            <span class="px-3 py-1 bg-slate-100 text-slate-500 text-[10px] font-bold uppercase rounded-full">
                                {{ $program->children_count }} Members
                            </span>
                        </div>

                        <h3 class="text-xl font-bold text-slate-900 mb-2 truncate">{{ $program->name }}</h3>
                        <p class="text-sm text-slate-500 line-clamp-2 mb-6">Join the conversation with students and mentors in
                            this specialized hub.</p>

                        @php
                            $rolePrefix = auth()->user()->hasRole('Admin') ? 'admin' : 'mentor';
                        @endphp
                        <a href="{{ route($rolePrefix . '.communities.show', $program->id) }}"
                            class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-[#0B4D73] text-white font-bold rounded-xl hover:bg-[#093e5d] transition-all shadow-lg shadow-blue-900/10">
                            <span>Open Chatroom</span>
                            <i class="fas fa-external-link-alt text-xs"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full glass rounded-3xl p-16 text-center border-2 border-dashed border-slate-200">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-users-slash text-4xl text-slate-300"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-2">No communities found</h3>
                    <p class="text-slate-500 max-w-sm mx-auto">You are not currently assigned to any program communities.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection