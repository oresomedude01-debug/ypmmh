@extends('layouts.dashboard')

@section('title', 'Edit Gallery Image')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('mentor.gallery.index') }}"
                class="p-2 rounded-lg hover:bg-slate-100 transition-all text-slate-500">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold">Edit Image</h2>
                <p class="text-slate-500 text-sm">Update gallery item details.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 p-4 rounded-xl text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('mentor.gallery.update', $gallery->id) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            <div class="glass p-8 rounded-2xl space-y-6">
                <div class="space-y-2">
                    <label for="title" class="block text-sm font-bold text-slate-700">Image Title</label>
                    <input type="text" name="title" id="title" class="w-full" value="{{ old('title', $gallery->title) }}"
                        required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="category" class="block text-sm font-bold text-slate-700">Category</label>
                        <select name="category" id="category" class="w-full" required>
                            @foreach(['workshops', 'events', 'mentoring', 'General'] as $cat)
                                <option value="{{ $cat }}" {{ $gallery->category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label for="status" class="block text-sm font-bold text-slate-700">Display Status</label>
                        <select name="status" id="status" class="w-full" required>
                            <option value="active" {{ $gallery->status === 'active' ? 'selected' : '' }}>Active (Visible)
                            </option>
                            <option value="inactive" {{ $gallery->status === 'inactive' ? 'selected' : '' }}>Inactive (Hidden)
                            </option>
                        </select>
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="block text-sm font-bold text-slate-700">Replace Image (Optional)</label>
                    <div class="relative group cursor-pointer">
                        <input type="file" name="image" id="gallery_image" class="hidden" accept="image/*"
                            onchange="previewImage(this)">
                        <div id="image-preview"
                            class="w-full aspect-video rounded-2xl bg-slate-50 border border-slate-200 flex flex-col items-center justify-center text-slate-400 group-hover:bg-slate-100 transition-all overflow-hidden"
                            onclick="document.getElementById('gallery_image').click()">
                            <img src="{{ Str::startsWith($gallery->image_path, 'http') ? $gallery->image_path : asset('storage/' . $gallery->image_path) }}"
                                class="w-full h-full object-cover">
                        </div>
                        <div class="mt-2 text-center">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-[#0B4D73]">Click image to
                                upload new photo</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2 p-4 bg-amber-50 rounded-xl border border-amber-100">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1"
                        class="rounded text-amber-500 focus:ring-amber-500" {{ $gallery->is_featured ? 'checked' : '' }}>
                    <label for="is_featured" class="text-sm font-bold text-amber-900">Feature this image on the gallery
                        page?</label>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="submit" class="btn btn-primary flex-1 justify-center py-4">
                        <i class="fas fa-save mr-2"></i> Update Gallery Item
                    </button>
                    <a href="{{ route('mentor.gallery.index') }}" class="btn btn-secondary px-8">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>

    @section('scripts')
        <script>
            function previewImage(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        const preview = document.getElementById('image-preview');
                        preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>
    @endsection
@endsection