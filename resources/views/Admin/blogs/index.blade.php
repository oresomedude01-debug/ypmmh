@extends('layouts.dashboard')

@section('title', 'Manage All Blogs')

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
                    System Blog
                </h1>
                <p class="font-medium" style="color: var(--text-secondary);">Monitor and manage all articles published across the platform.</p>
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
                <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary shadow-lg shadow-[#0B4D73]/20">
                    <i class="fas fa-plus"></i>
                    <span>Create New Post</span>
                </a>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="analytics-card rounded-2xl p-6 relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl stat-icon-bg bg-blue-50 text-blue-500 flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition-transform">
                            <i class="fas fa-newspaper"></i>
                        </div>
                    </div>
                    <p class="text-[10px] sm:text-sm font-bold uppercase tracking-wider mb-1" style="color: var(--text-secondary);">Total Posts</p>
                    <h3 class="text-2xl sm:text-3xl font-black" style="color: var(--text-primary);">{{ $posts->total() }}</h3>
                </div>
            </div>

            <div class="analytics-card rounded-2xl p-6 relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl stat-icon-bg bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition-transform">
                            <i class="fas fa-check-double"></i>
                        </div>
                    </div>
                    <p class="text-[10px] sm:text-sm font-bold uppercase tracking-wider mb-1" style="color: var(--text-secondary);">Published</p>
                    <h3 class="text-2xl sm:text-3xl font-black text-emerald-500">{{ $posts->where('status', 'published')->count() }}</h3>
                </div>
            </div>

            <div class="analytics-card rounded-2xl p-6 relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl stat-icon-bg bg-amber-50 text-amber-500 flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition-transform">
                            <i class="fas fa-pen-nib"></i>
                        </div>
                    </div>
                    <p class="text-[10px] sm:text-sm font-bold uppercase tracking-wider mb-1" style="color: var(--text-secondary);">Drafts</p>
                    <h3 class="text-2xl sm:text-3xl font-black text-amber-500">{{ $posts->where('status', 'draft')->count() }}</h3>
                </div>
            </div>

            <div class="analytics-card rounded-2xl p-6 relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl stat-icon-bg bg-purple-50 text-purple-500 flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition-transform">
                            <i class="fas fa-eye"></i>
                        </div>
                    </div>
                    <p class="text-[10px] sm:text-sm font-bold uppercase tracking-wider mb-1" style="color: var(--text-secondary);">Total Reads</p>
                    <h3 class="text-2xl sm:text-3xl font-black text-purple-600">{{ number_format($posts->sum('reads')) }}</h3>
                </div>
            </div>
        </div>

        <!-- Posts Grid View -->
        <div id="grid-view" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($posts as $post)
                <div class="glass rounded-3xl overflow-hidden border border-slate-100/50 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group flex flex-col">
                    <!-- Image -->
                    <div class="relative aspect-video overflow-hidden">
                        <img src="{{ Str::startsWith($post->featured_image, 'http') ? $post->featured_image : asset('storage/' . $post->featured_image) }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                            onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($post->title) }}&color=0B4D73&background=E0F2FE'">
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-white/90 backdrop-blur text-slate-900 border border-white/20">
                                {{ $post->category }}
                            </span>
                        </div>
                        <div class="absolute top-4 right-4">
                            @if($post->status === 'published')
                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-emerald-500 text-white shadow-lg shadow-emerald-500/30">
                                    Published
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-amber-500 text-white shadow-lg shadow-amber-500/30">
                                    Draft
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6 flex-1">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-600 border border-slate-200">
                                {{ substr($post->author->name, 0, 1) }}
                            </div>
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">{{ $post->author->name }}</span>
                            <span class="text-slate-300">•</span>
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">{{ $post->created_at->format('M d, Y') }}</span>
                        </div>
                        <h4 class="text-lg font-bold text-slate-900 mb-2 group-hover:text-[#0B4D73] transition-colors line-clamp-2">{{ $post->title }}</h4>
                        <p class="text-sm text-slate-500 line-clamp-2 mb-4">{{ $post->excerpt }}</p>

                        <div class="flex items-center gap-4 py-3 border-t border-slate-100">
                            <div class="flex items-center gap-1.5 text-slate-400">
                                <i class="fas fa-eye text-xs"></i>
                                <span class="text-[10px] font-black uppercase">{{ number_format($post->reads) }} Reads</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="p-4 bg-slate-50/50 border-t border-slate-100 grid grid-cols-3 gap-2">
                        <a href="{{ route('blog.show', $post->slug) }}" target="_blank"
                            class="flex items-center justify-center p-2.5 bg-white border border-slate-200 text-slate-400 hover:text-blue-600 hover:border-blue-100 rounded-xl transition-all shadow-sm"
                            title="View Publicly">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                        <a href="{{ route('admin.blogs.edit', $post->id) }}"
                            class="flex items-center justify-center p-2.5 bg-white border border-slate-200 text-slate-400 hover:text-amber-600 hover:border-amber-100 rounded-xl transition-all shadow-sm"
                            title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.blogs.destroy', $post->id) }}" method="POST"
                            onsubmit="return confirm('Delete this post?')" class="contents">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="flex items-center justify-center p-2.5 bg-white border border-slate-200 text-slate-400 hover:text-red-600 hover:border-red-100 rounded-xl transition-all shadow-sm"
                                title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full glass rounded-3xl p-24 text-center border-2 border-dashed border-slate-200">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-newspaper text-4xl text-slate-300"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-2">No Articles Found</h3>
                    <p class="text-slate-500">Initial posts will appear here once created.</p>
                </div>
            @endforelse
        </div>

        <!-- Posts Table View -->
        <div id="table-view" class="hidden overflow-x-auto glass rounded-3xl border border-slate-100/50">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest">Article</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest">Author</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Reads</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($posts as $post)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-8 rounded-lg overflow-hidden bg-slate-100 border border-slate-200">
                                        <img src="{{ Str::startsWith($post->featured_image, 'http') ? $post->featured_image : asset('storage/' . $post->featured_image) }}"
                                             class="w-full h-full object-cover"
                                             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($post->title) }}&color=0B4D73&background=E0F2FE'">
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 leading-none mb-1 group-hover:text-[#0B4D73] line-clamp-1">{{ $post->title }}</p>
                                        <p class="text-[10px] text-slate-500 mb-1 line-clamp-1 max-w-sm">{{ $post->excerpt }}</p>
                                        <p class="text-[9px] text-slate-400 font-medium">{{ $post->category }} • {{ $post->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-600 border border-slate-200">
                                        {{ substr($post->author->name, 0, 1) }}
                                    </div>
                                    <span class="text-xs font-bold text-slate-700">{{ $post->author->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($post->status === 'published')
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest border bg-emerald-50 text-emerald-600 border-emerald-100">Published</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest border bg-amber-50 text-amber-600 border-amber-100">Draft</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <p class="text-xs font-black text-slate-900">{{ number_format($post->reads) }}</p>
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-tighter">Views</p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="p-2 text-slate-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-all">
                                        <i class="fas fa-external-link-alt text-sm"></i>
                                    </a>
                                    <a href="{{ route('admin.blogs.edit', $post->id) }}" class="p-2 text-slate-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-all">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <form action="{{ route('admin.blogs.destroy', $post->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete post?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all">
                                            <i class="fas fa-trash-alt text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500 font-medium">No articles found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($posts->hasPages())
            <div class="pt-4">
                {{ $posts->links() }}
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
            localStorage.setItem('admin_blogs_view', mode);
        }

        document.addEventListener('DOMContentLoaded', () => {
            const savedMode = localStorage.getItem('admin_blogs_view') || 'table';
            setViewMode(savedMode);
        });
    </script>
@endsection
