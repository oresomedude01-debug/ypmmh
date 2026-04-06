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
    <div class="space-y-6 md:space-y-8 animate-fade-in">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl md:text-3xl font-black bg-gradient-to-r from-[#0B4D73] to-blue-500 bg-clip-text text-transparent leading-tight">
                    System Blog
                </h1>
                <p class="text-[10px] md:text-sm font-medium" style="color: var(--text-secondary);">Manage all articles and blog contents across the platform</p>
            </div>
            <div class="flex items-center gap-3">
                <!-- View Toggle Toggle -->
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

                <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary shadow-lg shadow-[#0B4D73]/20 py-2 px-4 md:py-2.5 md:px-5">
                    <i class="fas fa-plus text-xs md:text-sm"></i>
                    <span class="text-xs md:text-sm">Create Post</span>
                </a>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
            <!-- Total Posts -->
            <div class="admin-card p-3 md:p-6 relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-2 md:mb-4">
                        <div class="w-8 h-8 md:w-12 md:h-12 rounded-lg md:rounded-xl bg-blue-500/10 text-blue-500 flex items-center justify-center text-xs md:text-xl shadow-sm group-hover:scale-110 transition-transform">
                            <i class="fas fa-newspaper"></i>
                        </div>
                    </div>
                    <p class="text-[8px] md:text-[10px] font-black uppercase tracking-widest mb-0.5 md:mb-1 truncate" style="color: var(--text-secondary);">
                        Total Articles</p>
                    <h3 class="text-lg md:text-3xl font-black" style="color: var(--text-primary);">{{ $posts->total() }}</h3>
                </div>
                <div class="absolute -right-4 -bottom-4 w-12 h-12 md:w-24 md:h-24 bg-blue-500/5 rounded-full group-hover:scale-125 transition-transform"></div>
            </div>

            <!-- Published Posts -->
            <div class="admin-card p-3 md:p-6 relative overflow-hidden group border-l-2 md:border-l-4 border-l-emerald-500">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-2 md:mb-4">
                        <div class="w-8 h-8 md:w-12 md:h-12 rounded-lg md:rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center text-xs md:text-xl shadow-sm group-hover:scale-110 transition-transform">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <p class="text-[8px] md:text-[10px] font-black uppercase tracking-widest mb-0.5 md:mb-1 truncate" style="color: var(--text-secondary);">
                        Published</p>
                    <h3 class="text-lg md:text-3xl font-black text-emerald-600">{{ $posts->where('status', 'published')->count() }}</h3>
                </div>
                <div class="absolute -right-4 -bottom-4 w-12 h-12 md:w-24 md:h-24 bg-emerald-500/5 rounded-full group-hover:scale-125 transition-transform"></div>
            </div>

            <!-- Draft Posts -->
            <div class="admin-card p-3 md:p-6 relative overflow-hidden group border-l-2 md:border-l-4 border-l-amber-500">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-2 md:mb-4">
                        <div class="w-8 h-8 md:w-12 md:h-12 rounded-lg md:rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center text-xs md:text-xl shadow-sm group-hover:scale-110 transition-transform">
                            <i class="fas fa-edit"></i>
                        </div>
                    </div>
                    <p class="text-[8px] md:text-[10px] font-black uppercase tracking-widest mb-0.5 md:mb-1 truncate" style="color: var(--text-secondary);">
                        Drafts</p>
                    <h3 class="text-lg md:text-3xl font-black text-amber-600">{{ $posts->where('status', 'draft')->count() }}</h3>
                </div>
                <div class="absolute -right-4 -bottom-4 w-12 h-12 md:w-24 md:h-24 bg-amber-500/5 rounded-full group-hover:scale-125 transition-transform"></div>
            </div>

            <!-- Total Reads -->
            <div class="admin-card p-3 md:p-6 relative overflow-hidden group border-l-2 md:border-l-4 border-l-purple-500">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-2 md:mb-4">
                        <div class="w-8 h-8 md:w-12 md:h-12 rounded-lg md:rounded-xl bg-purple-500/10 text-purple-500 flex items-center justify-center text-xs md:text-xl shadow-sm group-hover:scale-110 transition-transform">
                            <i class="fas fa-eye"></i>
                        </div>
                    </div>
                    <p class="text-[8px] md:text-[10px] font-black uppercase tracking-widest mb-0.5 md:mb-1 truncate" style="color: var(--text-secondary);">
                        Total Reads</p>
                    <h3 class="text-lg md:text-3xl font-black text-purple-600">{{ number_format($posts->sum('reads')) }}</h3>
                </div>
                <div class="absolute -right-4 -bottom-4 w-12 h-12 md:w-24 md:h-24 bg-purple-500/5 rounded-full group-hover:scale-125 transition-transform"></div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="glass rounded-2xl p-4 md:p-6 border shadow-sm" style="border-color: var(--border-color);">
            <form action="{{ route('admin.blogs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3 md:gap-4 items-end">
                <div>
                    <label class="block text-[9px] md:text-xs font-black uppercase tracking-widest mb-1.5 md:mb-2" style="color: var(--text-secondary);">Category</label>
                    <select name="category" class="w-full px-3 md:px-4 py-2 md:py-2.5 rounded-xl border text-xs md:text-sm focus:outline-none focus:ring-4 focus:ring-[#0B4D73]/10 transition-all cursor-pointer" style="background-color: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);">
                        <option value="">All Categories</option>
                        @foreach($posts->pluck('category')->unique() as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="hidden md:block">
                    <label class="block text-xs font-black uppercase tracking-widest mb-2" style="color: var(--text-secondary);">Status</label>
                    <select name="status" class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-4 focus:ring-[#0B4D73]/10 transition-all cursor-pointer" style="background-color: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);">
                        <option value="">All Statuses</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[9px] md:text-xs font-black uppercase tracking-widest mb-1.5 md:mb-2" style="color: var(--text-secondary);">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title..." class="w-full px-3 md:px-4 py-2 md:py-2.5 rounded-xl border text-xs md:text-sm focus:outline-none focus:ring-4 focus:ring-[#0B4D73]/10 transition-all" style="background-color: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);">
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-[#0B4D73] text-white rounded-xl py-2 md:py-2.5 text-xs md:text-sm font-bold hover:brightness-110 transition-all shadow-sm">
                        Filter
                    </button>
                    <a href="{{ route('admin.blogs.index') }}" class="px-3 md:px-4 py-2 md:py-2.5 border rounded-xl transition-colors flex items-center justify-center text-xs md:text-sm" style="border-color: var(--border-color); color: var(--text-secondary);" title="Reset Filters">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Desktop Views (Grid & Table) -->
        <div class="hidden lg:block">
            <!-- Blogs Grid View -->
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
                            <h4 class="text-lg font-bold text-slate-900 mb-2 group-hover:text-[#0B4D73] transition-colors line-clamp-1">{{ $post->title }}</h4>
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
                        <p class="text-slate-500">Articles will appear here once created.</p>
                    </div>
                @endforelse
            </div>

            <!-- Table View -->
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
                        @foreach($posts as $post)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-8 rounded-lg overflow-hidden bg-slate-100 border border-slate-200 flex-shrink-0">
                                            <img src="{{ Str::startsWith($post->featured_image, 'http') ? $post->featured_image : asset('storage/' . $post->featured_image) }}"
                                                 class="w-full h-full object-cover"
                                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($post->title) }}&color=0B4D73&background=E0F2FE'">
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 leading-none mb-1 group-hover:text-[#0B4D73] line-clamp-1">{{ $post->title }}</p>
                                            <p class="text-[9px] text-slate-400 font-medium tracking-wide uppercase">{{ $post->category }} • {{ $post->created_at->format('M d, Y') }}</p>
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
                                        <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="p-2 text-slate-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-all" title="View">
                                            <i class="fas fa-external-link-alt text-sm"></i>
                                        </a>
                                        <a href="{{ route('admin.blogs.edit', $post->id) }}" class="p-2 text-slate-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-all" title="Edit">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        <form action="{{ route('admin.blogs.destroy', $post->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete post?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Delete">
                                                <i class="fas fa-trash-alt text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile List View -->
        <div class="lg:hidden space-y-2.5">
            @forelse($posts as $post)
                <div class="admin-card p-3.5 flex flex-col gap-2.5 rounded-2xl glass border border-slate-100/50 relative overflow-hidden group">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <!-- Image Icon -->
                            <div class="w-8 h-8 shrink-0 rounded-lg overflow-hidden bg-slate-100 border border-slate-200/50">
                                <img src="{{ Str::startsWith($post->featured_image, 'http') ? $post->featured_image : asset('storage/' . $post->featured_image) }}"
                                     class="w-full h-full object-cover"
                                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($post->title) }}&color=0B4D73&background=E0F2FE'">
                            </div>
                            <!-- Info -->
                            <div class="min-w-0">
                                <h4 class="font-bold text-[13px] text-slate-900 truncate tracking-tight leading-tight">
                                    {{ $post->title }}
                                </h4>
                                <span class="inline-block mt-0.5 px-1.5 py-0.5 rounded-md bg-slate-100 text-slate-600 text-[7px] font-black uppercase tracking-widest border border-slate-200">
                                    {{ $post->category }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer/Actions -->
                    <div class="flex items-center justify-between pt-2.5 border-t border-slate-100/50">
                        <div class="flex items-center gap-2">
                            <!-- Status -->
                            @if($post->status === 'published')
                                <span class="px-1.5 py-0.5 rounded-full text-[7px] font-black uppercase tracking-widest border bg-emerald-50 text-emerald-600 border-emerald-100">Published</span>
                            @else
                                <span class="px-1.5 py-0.5 rounded-full text-[7px] font-black uppercase tracking-widest border bg-amber-50 text-amber-600 border-amber-100">Draft</span>
                            @endif
                            
                            <!-- Reads Count -->
                            <div class="flex items-center gap-1 text-[8px] font-bold text-slate-500 bg-slate-50 px-1.5 py-0.5 rounded-md border border-slate-100">
                                <i class="fas fa-eye text-[#0B4D73] text-[7px]"></i>
                                {{ number_format($post->reads) }}
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-1.5 shrink-0">
                            <a href="{{ route('blog.show', $post->slug) }}" target="_blank"
                                class="w-7 h-7 flex items-center justify-center rounded-lg bg-slate-50 text-slate-500 hover:text-blue-600 transition-colors border border-slate-100">
                                <i class="fas fa-external-link-alt text-[10px]"></i>
                            </a>
                            <a href="{{ route('admin.blogs.edit', $post->id) }}"
                                class="w-7 h-7 flex items-center justify-center rounded-lg bg-blue-50 text-blue-500 border border-blue-100">
                                <i class="fas fa-edit text-[10px]"></i>
                            </a>
                            <form action="{{ route('admin.blogs.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Delete post?');" class="contents">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-7 h-7 flex items-center justify-center rounded-lg bg-red-50 text-red-500 border border-red-100">
                                    <i class="fas fa-trash-alt text-[10px]"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="glass rounded-3xl p-8 sm:p-16 text-center border-2 border-dashed border-slate-200">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-newspaper text-3xl text-slate-300"></i>
                    </div>
                    <h3 class="text-sm font-black text-slate-900 mb-1">No Articles Found</h3>
                    <p class="text-[10px] font-medium text-slate-500">Articles will appear here once created.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($posts->hasPages())
            <div class="pt-6 pb-2 border-t mt-4 md:mt-6" style="border-color: var(--border-color);">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
@endsection

@section('scripts')
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
