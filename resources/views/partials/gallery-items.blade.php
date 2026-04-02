@foreach($images as $image)
    <div class="masonry-item gallery-item relative overflow-hidden rounded-xl shadow-sm border border-slate-100 bg-white cursor-pointer group"
        data-category="{{ $image->category }}"
        onclick="openLightbox('{{ Str::startsWith($image->image_path, 'http') ? $image->image_path : asset('storage/' . $image->image_path) }}', '{{ $image->title }}', '{{ $image->category }}')">

        <div class="relative overflow-hidden" style="min-height: 200px;">
            <img src="{{ Str::startsWith($image->image_path, 'http') ? $image->image_path : asset('storage/' . $image->image_path) }}"
                alt="{{ $image->title }}"
                class="w-full h-full object-cover transition-all duration-500 group-hover:scale-105" loading="lazy"
                onerror="this.src='https://images.unsplash.com/photo-1523240715639-93f8faa0effb?auto=format&fit=crop&w=600&q=80'">

            <!-- Overlay -->
            <div
                class="gallery-overlay absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent opacity-0 transition-opacity duration-300 flex flex-col justify-end p-4">
                <span class="text-white font-bold text-sm leading-tight">{{ $image->title }}</span>
                <span class="text-white/70 text-xs mt-1 capitalize">
                    <i class="fas fa-tag mr-1"></i>{{ $image->category }}
                </span>
            </div>

            <!-- Category pill -->
            <div class="absolute top-3 left-3">
                <span
                    class="px-2 py-1 bg-white/90 backdrop-blur-sm rounded-md text-[9px] font-bold uppercase text-slate-700 shadow-sm">
                    {{ $image->category }}
                </span>
            </div>

            <!-- Featured badge -->
            @if($image->is_featured)
                <div class="absolute top-3 right-3 float-gentle">
                    <span
                        class="w-7 h-7 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-lg">
                        <i class="fas fa-star text-white text-[10px]"></i>
                    </span>
                </div>
            @endif

            <!-- Zoom icon on hover -->
            <div
                class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <div class="w-12 h-12 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center shadow-lg">
                    <i class="fas fa-search-plus text-[#0B4D73]"></i>
                </div>
            </div>
        </div>
    </div>
@endforeach