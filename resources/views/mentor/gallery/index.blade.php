@extends('layouts.dashboard')

@section('title', 'Manage My Gallery')

    @section('styles')
        <style>
            .analytics-card {
                background: var(--bg-secondary);
                border: 1px solid var(--border-color);
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: var(--card-shadow, 0 4px 6px -1px rgba(0, 0, 0, 0.1));
            }

            .analytics-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 20px 25px -5px var(--shadow-color);
                border-color: var(--primary-500);
            }

            /* Themed utility overrides */
            [data-theme="dark"] .stat-icon-bg {
                background: rgba(255, 255, 255, 0.05);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
        </style>
    @endsection

    @section('content')
        <div class="space-y-8 animate-fade-in">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-black bg-gradient-to-r from-[#0B4D73] to-blue-500 bg-clip-text text-transparent">
                        My Gallery
                    </h1>
                    <p class="font-medium" style="color: var(--text-secondary);">Upload and manage images you've shared with the
                        community.</p>
                </div>
                <div class="flex items-center gap-3">
                    <!-- View Toggle -->
                    <div class="hidden lg:flex items-center bg-slate-100 rounded-xl p-1 border border-slate-200">
                        <button onclick="setViewMode('table')" id="table-view-btn"
                            class="px-3 py-2 rounded-lg transition-all duration-200 flex items-center gap-2 text-xs font-bold">
                            <i class="fas fa-list"></i>
                            <span>Table</span>
                        </button>
                        <button onclick="setViewMode('grid')" id="grid-view-btn"
                            class="px-3 py-2 rounded-lg transition-all duration-200 flex items-center gap-2 text-xs font-bold">
                            <i class="fas fa-th-large"></i>
                            <span>Grid</span>
                        </button>
                    </div>
                    <a href="{{ route('mentor.gallery.create') }}" class="btn btn-primary shadow-lg shadow-[#0B4D73]/20">
                        <i class="fas fa-plus"></i>
                        <span>Add New Image</span>
                    </a>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="analytics-card rounded-2xl p-6 relative overflow-hidden group">
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="w-12 h-12 rounded-xl stat-icon-bg bg-blue-50 text-blue-500 flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition-transform">
                                <i class="fas fa-images"></i>
                            </div>
                        </div>
                        <p class="text-[10px] sm:text-sm font-bold uppercase tracking-wider mb-1"
                            style="color: var(--text-secondary);">Your Uploads</p>
                        <h3 class="text-2xl sm:text-3xl font-black" style="color: var(--text-primary);">{{ $images->total() }}
                        </h3>
                    </div>
                </div>

                <div class="analytics-card rounded-2xl p-6 relative overflow-hidden group">
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="w-12 h-12 rounded-xl stat-icon-bg bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition-transform">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                        <p class="text-[10px] sm:text-sm font-bold uppercase tracking-wider mb-1"
                            style="color: var(--text-secondary);">Active</p>
                        <h3 class="text-2xl sm:text-3xl font-black text-emerald-500">
                            {{ $images->where('status', 'active')->count() }}</h3>
                    </div>
                </div>

                <div class="analytics-card rounded-2xl p-6 relative overflow-hidden group">
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="w-12 h-12 rounded-xl stat-icon-bg bg-amber-50 text-amber-500 flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition-transform">
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <p class="text-[10px] sm:text-sm font-bold uppercase tracking-wider mb-1"
                            style="color: var(--text-secondary);">Featured</p>
                        <h3 class="text-2xl sm:text-3xl font-black text-amber-500">
                            {{ $images->where('is_featured', true)->count() }}</h3>
                    </div>
                </div>
            </div>

            <!-- Gallery Grid View -->
            <div id="grid-view" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($images as $image)
                    <div
                        class="glass rounded-3xl overflow-hidden group border border-slate-100/50 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col">
                        <div class="aspect-video relative overflow-hidden bg-slate-100">
                            <img src="{{ Str::startsWith($image->image_path, 'http') ? $image->image_path : asset('storage/' . $image->image_path) }}"
                                class="w-full h-full object-cover transition-all duration-500 group-hover:scale-110">

                            <div class="absolute top-3 left-3 flex flex-col gap-2">
                                <span
                                    class="px-2 py-1 rounded-lg bg-white/90 backdrop-blur-sm text-[9px] font-black uppercase text-slate-800 border border-white/20 shadow-sm self-start">
                                    {{ $image->category }}
                                </span>
                            </div>

                            @if($image->is_featured)
                                <div class="absolute top-3 right-3">
                                    <div
                                        class="w-8 h-8 rounded-full bg-amber-400 text-white flex items-center justify-center shadow-lg shadow-amber-400/30">
                                        <i class="fas fa-star text-[10px]"></i>
                                    </div>
                                </div>
                            @endif

                            <div
                                class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
                                <a href="{{ route('mentor.gallery.edit', $image->id) }}"
                                    class="w-10 h-10 rounded-xl bg-white text-slate-900 flex items-center justify-center hover:bg-[#0B4D73] hover:text-white transition-all transform hover:scale-110">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('mentor.gallery.destroy', $image->id) }}" method="POST"
                                    onsubmit="return confirm('Remove this image from the gallery?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-10 h-10 rounded-xl bg-white text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition-all transform hover:scale-110">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="p-4 flex-1 flex flex-col">
                            <h3 class="font-bold text-slate-900 text-sm truncate mb-3">{{ $image->title }}</h3>
                            <div class="mt-auto flex items-center justify-between border-t border-slate-50 pt-3">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-black uppercase border {{ $image->status === 'active' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-slate-50 text-slate-400 border-slate-100' }}">
                                    {{ $image->status }}
                                </span>
                                <span
                                    class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $image->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full glass rounded-3xl p-24 text-center border-2 border-dashed border-slate-200">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-camera text-4xl text-slate-300"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-2">No Images Yet</h3>
                        <p class="text-slate-500 max-w-sm mx-auto mb-6">Start showcasing moments and experiences with the community.
                        </p>
                        <a href="{{ route('mentor.gallery.create') }}" class="btn btn-primary">Upload First Image</a>
                    </div>
                @endforelse
            </div>

            <!-- Gallery Table View -->
            <div id="table-view" class="hidden overflow-x-auto glass rounded-3xl border border-slate-100/50">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest">Media</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest">Category</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">
                                Status</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest text-right">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($images as $image)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-8 rounded-lg overflow-hidden bg-slate-100 border border-slate-200">
                                            <img src="{{ Str::startsWith($image->image_path, 'http') ? $image->image_path : asset('storage/' . $image->image_path) }}"
                                                class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <p
                                                class="font-bold text-slate-900 leading-none mb-1 group-hover:text-[#0B4D73] line-clamp-1">
                                                {{ $image->title }}</p>
                                            <p class="text-[10px] text-slate-400 font-medium">
                                                {{ $image->created_at->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 rounded-md bg-slate-100 text-slate-600 text-[9px] font-black uppercase tracking-widest">{{ $image->category }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $image->status === 'active' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-slate-50 text-slate-400 border-slate-100' }}">
                                        {{ $image->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('mentor.gallery.edit', $image->id) }}"
                                            class="p-2 text-slate-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-all">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        <form action="{{ route('mentor.gallery.destroy', $image->id) }}" method="POST"
                                            class="inline-block" onsubmit="return confirm('Delete image?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all">
                                                <i class="fas fa-trash-alt text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-500 font-medium">No media found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($images->hasPages())
                <div class="pt-4">
                    {{ $images->links() }}
                </div>
            @endif
        </div>

        <script>
            function setViewMode(mode) {
                const gridView = document.getElementById('grid-view');
                const tableView = document.getElementById('table-view');
                const gridBtn = document.getElementById('grid-view-btn');
                const tableBtn = document.getElementById('table-view-btn');

                if (!gridView || !tableView) return;

                if (mode === 'grid') {
                    gridView.classList.remove('hidden');
                    tableView.classList.add('hidden');
                    gridBtn.classList.add('bg-white', 'text-[#0B4D73]', 'shadow-sm');
                    gridBtn.classList.remove('text-slate-500');
                    tableBtn.classList.remove('bg-white', 'text-[#0B4D73]', 'shadow-sm');
                    tableBtn.classList.add('text-slate-500');
                } else {
                    gridView.classList.add('hidden');
                    tableView.classList.remove('hidden');
                    tableBtn.classList.add('bg-white', 'text-[#0B4D73]', 'shadow-sm');
                    tableBtn.classList.remove('text-slate-500');
                    gridBtn.classList.remove('bg-white', 'text-[#0B4D73]', 'shadow-sm');
                    gridBtn.classList.add('text-slate-500');
                }
                localStorage.setItem('mentor_gallery_view', mode);
            }

            document.addEventListener('DOMContentLoaded', () => {
                const savedMode = localStorage.getItem('mentor_gallery_view') || 'grid';
                setViewMode(savedMode);
            });
        </script>
    @endsection