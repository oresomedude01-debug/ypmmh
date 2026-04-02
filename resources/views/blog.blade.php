@extends('layouts.public')

@section('title', 'YPMMH Blog | Islamic Parenting & Child Development Insights')
@section('description', 'Read our latest articles on raising confident Muslim children, Islamic parenting tips, and the importance of mentorship in youth development.')
@section('keywords', 'islamic parenting blog, raising muslim children, child psychology from islamic perspective, youth mentorship methodology')

@section('content')
    <div class="max-w-7xl mx-auto px-6 py-6 md:py-8">
        <div class="max-w-7xl mx-auto px-0 md:px-6">
            <!-- Compact Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8 border-b border-slate-100 pb-6">
                <div class="space-y-1">
                    <div
                        class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full bg-blue-50 text-[#0B4D73] text-[9px] font-bold uppercase tracking-widest border border-blue-100">
                        <i class="fas fa-pen-nib"></i> Community Blog
                    </div>
                    <h1 class="text-2xl md:text-3xl font-black text-slate-900 leading-tight">
                        Insights & <span class="text-primary">Stories</span>
                    </h1>
                </div>
                <p class="text-xs md:text-sm text-slate-500 font-medium max-w-md text-left md:text-right leading-relaxed">
                    Expert parenting tips, student success stories, and spiritual reflections from our global community.
                </p>
            </div>

            @if($featuredPost)
                <!-- Featured Post -->
                <a href="{{ route('blog.show', $featuredPost->slug) }}"
                    class="glass p-5 md:p-8 rounded-[2rem] flex flex-col lg:flex-row gap-6 items-center mb-12 border border-white shadow-xl group hover:border-blue-200 transition-all">
                    <div class="lg:w-1/2 w-full aspect-video overflow-hidden rounded-[1.5rem]">
                        <img src="{{ Str::startsWith($featuredPost->featured_image, 'http') ? $featuredPost->featured_image : asset('storage/' . $featuredPost->featured_image) }}"
                            alt="{{ $featuredPost->title }}"
                            class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105"
                            onerror="this.src='https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?auto=format&fit=crop&w=1200&q=80'">
                    </div>
                    <div class="lg:w-1/2 w-full space-y-4">
                        <div class="flex items-center gap-3">
                            <span
                                class="px-3 py-1 rounded-full bg-blue-50 text-[#0B4D73] text-[9px] font-bold uppercase tracking-widest border border-blue-100">Featured</span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                {{ $featuredPost->published_at->format('M d, Y') }} •
                                {{ round(str_word_count(strip_tags($featuredPost->content)) / 200) }} min read
                            </span>
                        </div>
                        <h2
                            class="text-xl md:text-2xl font-bold text-slate-900 leading-tight group-hover:text-blue-600 transition-colors">
                            {{ $featuredPost->title }}
                        </h2>
                        <p class="text-slate-500 text-sm leading-relaxed line-clamp-3">
                            {{ $featuredPost->excerpt }}
                        </p>
                        <div class="flex items-center gap-3 pt-2">
                            <div
                                class="w-8 h-8 rounded-xl overflow-hidden border border-white shadow-md bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-400">
                                @if($featuredPost->author->profile_photo_url)
                                    <img src="{{ $featuredPost->author->profile_photo_url }}" class="w-full h-full object-cover">
                                @else
                                    {{ substr($featuredPost->author->name, 0, 1) }}
                                @endif
                            </div>
                            <div>
                                <p class="font-bold text-slate-900 text-xs">{{ $featuredPost->author->name }}</p>
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">
                                    {{ $featuredPost->author->roles->first()?->name ?? 'Contributor' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            @endif

            <!-- Recent Articles Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                @foreach($posts as $post)
                    <a href="{{ route('blog.show', $post->slug) }}" class="group">
                        <div class="relative aspect-video rounded-2xl overflow-hidden mb-5 shadow-sm border border-slate-100">
                            <img src="{{ Str::startsWith($post->featured_image, 'http') ? $post->featured_image : asset('storage/' . $post->featured_image) }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                onerror="this.src='https://images.unsplash.com/photo-1585829365295-ab7cd400c167?auto=format&fit=crop&w=600'">
                            <div
                                class="absolute inset-x-0 bottom-0 p-3 bg-gradient-to-t from-black/60 to-transparent flex items-end">
                                <span
                                    class="px-2 py-0.5 bg-white/20 backdrop-blur-md rounded-md text-[8px] font-bold text-white uppercase tracking-widest">
                                    {{ $post->category }}
                                </span>
                            </div>
                        </div>
                        <h4 class="text-lg font-bold text-slate-900 mb-2 group-hover:text-blue-600 transition-colors">
                            {{ $post->title }}
                        </h4>
                        <p class="text-slate-500 text-xs leading-relaxed line-clamp-2 mb-4">
                            {{ $post->excerpt }}
                        </p>
                        <div class="flex items-center justify-between pt-3 border-t border-slate-50">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                                {{ $post->published_at->format('M d, Y') }}
                            </span>
                            <span
                                class="text-blue-600 text-[9px] font-bold uppercase tracking-widest group-hover:translate-x-1 transition-transform">Read
                                Story <i class="fas fa-arrow-right ml-1"></i></span>
                        </div>
                    </a>
                @endforeach
            </div>

            @if($posts->hasPages())
                <div class="mb-12">
                    {{ $posts->links() }}
                </div>
            @endif

            @if($posts->isEmpty() && !$featuredPost)
                <div class="py-24 text-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-newspaper text-3xl text-slate-300"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-900">No Articles Yet</h2>
                    <p class="text-slate-500 max-w-sm mx-auto mt-2">We are currently preparing some insightful stories for you.
                        Please check back later.</p>
                </div>
            @endif

            <!-- Newsletter Section -->
            <div
                class="mt-24 relative overflow-hidden rounded-[2.5rem] bg-slate-900 text-white p-12 text-center space-y-6 shadow-2xl">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32"></div>
                <h2 class="text-3xl font-bold">Stay Informed.</h2>
                <p class="text-slate-400 text-base max-w-lg mx-auto">Get monthly curated insights on parenting, youth
                    leadership, and faith-driven growth.</p>
                <form class="max-w-md mx-auto flex flex-col sm:flex-row gap-3">
                    <input type="email" placeholder="Your email..."
                        class="flex-1 px-6 py-4 rounded-xl bg-white/5 border border-white/10 focus:bg-white/10 focus:outline-none focus:border-blue-500 transition-all text-white text-sm">
                    <button
                        class="px-8 py-4 bg-[#0B4D73] text-white rounded-xl font-bold uppercase tracking-widest text-[10px] hover:bg-blue-700 transition-all shadow-xl shadow-blue-500/10">Subscribe</button>
                </form>
            </div>
        </div>
    </div>
@endsection