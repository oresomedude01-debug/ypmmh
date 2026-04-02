@extends('layouts.public')

@section('title', $post->title . ' | YPMMH Blog')
@section('description', $post->excerpt)

@section('content')
    <article class="max-w-7xl mx-auto px-6 py-6 md:py-12">
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-8">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
            <i class="fas fa-chevron-right text-[8px]"></i>
            <a href="{{ route('blog') }}" class="hover:text-primary transition-colors">Blog</a>
            <i class="fas fa-chevron-right text-[8px]"></i>
            <span class="text-slate-900 truncate max-w-[200px]">{{ $post->title }}</span>
        </nav>

        <div class="flex flex-col lg:flex-row gap-12">
            <!-- Main Content -->
            <div class="lg:w-2/3 space-y-8">
                <!-- Header -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <span
                            class="px-3 py-1 rounded-full bg-blue-50 text-[#0B4D73] text-[9px] font-bold uppercase tracking-widest border border-blue-100">
                            {{ $post->category }}
                        </span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            {{ $post->published_at->format('M d, Y') }} •
                            {{ round(str_word_count(strip_tags($post->content)) / 200) }} min read
                        </span>
                    </div>
                    <h1 class="text-3xl md:text-5xl font-black text-slate-900 leading-tight">
                        {{ $post->title }}
                    </h1>

                    <div class="flex items-center gap-4 pt-4 border-b border-slate-100 pb-8">
                        <div
                            class="w-12 h-12 rounded-2xl overflow-hidden border-2 border-white shadow-lg bg-slate-100 flex items-center justify-center text-lg font-bold text-slate-400">
                            @if($post->author->profile_photo_url)
                                <img src="{{ $post->author->profile_photo_url }}" class="w-full h-full object-cover">
                            @else
                                {{ substr($post->author->name, 0, 1) }}
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">{{ $post->author->name }}</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                {{ $post->author->roles->first()?->name ?? 'Contributor' }}
                            </p>
                        </div>
                        <div class="ml-auto flex items-center gap-2">
                            <button
                                class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-blue-50 hover:text-blue-600 transition-all">
                                <i class="fab fa-twitter"></i>
                            </button>
                            <button
                                class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-blue-50 hover:text-blue-600 transition-all">
                                <i class="fab fa-facebook-f"></i>
                            </button>
                            <button
                                class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-blue-50 hover:text-blue-600 transition-all">
                                <i class="fas fa-link"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Featured Image -->
                <div class="aspect-video rounded-[2.5rem] overflow-hidden shadow-2xl">
                    <img src="{{ Str::startsWith($post->featured_image, 'http') ? $post->featured_image : asset('storage/' . $post->featured_image) }}"
                        alt="{{ $post->title }}" class="w-full h-full object-cover"
                        onerror="this.src='https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?auto=format&fit=crop&w=1200&q=80'">
                </div>

                <!-- Article Content -->
                <div class="prose prose-slate prose-lg max-w-none 
                                prose-headings:font-black prose-headings:text-slate-900
                                prose-p:text-slate-600 prose-p:leading-relaxed
                                prose-li:text-slate-600
                                prose-img:rounded-3xl prose-img:shadow-xl">
                    {!! $post->content !!}
                </div>

                <!-- Tags/Footer -->
                <div class="pt-12 border-t border-slate-100">
                    <div class="flex flex-wrap gap-2">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest mr-2 py-1">Filed
                            under:</span>
                        <a href="#"
                            class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold uppercase tracking-widest hover:bg-primary hover:text-white transition-all">
                            {{ $post->category }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:w-1/3 space-y-12">
                <!-- Search -->
                <div class="glass p-8 rounded-[2rem] space-y-4">
                    <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-4">Search Articles</h3>
                    <form action="{{ route('blog') }}" method="GET" class="relative">
                        <input type="text" name="search" placeholder="Type keywords..."
                            class="w-full pl-12 pr-4 py-4 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-primary/20 transition-all text-sm">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                    </form>
                </div>

                @if($relatedPosts->isNotEmpty())
                    <!-- Related Posts -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <span class="w-8 h-1 bg-primary rounded-full"></span>
                            Related Stories
                        </h3>
                        <div class="grid gap-6">
                            @foreach($relatedPosts as $related)
                                <a href="{{ route('blog.show', $related->slug) }}" class="flex gap-4 group">
                                    <div
                                        class="w-24 h-24 rounded-2xl overflow-hidden flex-shrink-0 shadow-sm border border-slate-100">
                                        <img src="{{ Str::startsWith($related->featured_image, 'http') ? $related->featured_image : asset('storage/' . $related->featured_image) }}"
                                            class="w-full h-full object-cover transition-transform group-hover:scale-110"
                                            onerror="this.src='https://images.unsplash.com/photo-1585829365295-ab7cd400c167?auto=format&fit=crop&w=600'">
                                    </div>
                                    <div class="space-y-1">
                                        <h4
                                            class="text-sm font-bold text-slate-900 group-hover:text-primary transition-colors line-clamp-2">
                                            {{ $related->title }}
                                        </h4>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                            {{ $related->published_at->format('M d, Y') }}
                                        </p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Newsletter -->
                <div
                    class="bg-slate-900 rounded-[2rem] p-8 text-white space-y-6 relative overflow-hidden shadow-2xl shadow-blue-900/20">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16"></div>
                    <div class="space-y-2 relative">
                        <h3 class="text-xl font-bold">The Weekly Insight</h3>
                        <p class="text-slate-400 text-xs leading-relaxed">
                            Join 2,000+ parents receiving our curated mentorship tips.
                        </p>
                    </div>
                    <form class="space-y-3 relative">
                        <input type="email" placeholder="Your email..."
                            class="w-full px-5 py-4 rounded-xl bg-white/5 border border-white/10 focus:bg-white/10 focus:outline-none focus:border-blue-500 transition-all text-white text-xs">
                        <button
                            class="w-full py-4 bg-[#0B4D73] text-white rounded-xl font-bold uppercase tracking-widest text-[10px] hover:bg-blue-700 transition-all shadow-xl shadow-blue-500/10">
                            Subscribe Now
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </article>
@endsection