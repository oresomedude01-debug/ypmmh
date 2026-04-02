@extends('layouts.dashboard')

@section('title', 'Add New Gallery Image (Admin)')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.gallery.index') }}"
                class="p-2 rounded-lg hover:bg-slate-100 transition-all text-slate-500">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold">Add Image</h2>
                <p class="text-slate-500 text-sm">Upload a new memory to the community gallery.</p>
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

        <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="glass p-8 rounded-2xl space-y-6">
                <div class="space-y-2">
                    <label for="title" class="block text-sm font-bold text-slate-700">Image Title</label>
                    <input type="text" name="title" id="title" class="w-full" placeholder="e.g. Graduation Ceremony 2026"
                        required value="{{ old('title') }}">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="category" class="block text-sm font-bold text-slate-700">Category</label>
                        <select name="category" id="category" class="w-full" required>
                            <option value="workshops">Workshops</option>
                            <option value="events">Events</option>
                            <option value="mentoring">Mentoring</option>
                            <option value="General">General</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label for="status" class="block text-sm font-bold text-slate-700">Display Status</label>
                        <select name="status" id="status" class="w-full" required>
                            <option value="active">Active (Visible)</option>
                            <option value="inactive">Inactive (Hidden)</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="block text-sm font-bold text-slate-700">Gallery Image</label>
                    <div class="relative group cursor-pointer">
                        <input type="file" name="image" id="gallery_image" class="hidden" accept="image/*"
                            onchange="previewImage(this)" required>
                        <div id="image-preview"
                            class="w-full aspect-video rounded-2xl bg-slate-50 border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400 group-hover:bg-slate-100 group-hover:border-primary transition-all overflow-hidden"
                            onclick="document.getElementById('gallery_image').click()">
                            <i class="fas fa-image text-3xl mb-4"></i>
                            <span class="text-xs font-bold uppercase tracking-widest text-slate-500">Click to upload
                                photo</span>
                            <p class="text-[10px] mt-2 text-slate-400">JPG, PNG, GIF up to 5MB</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2 p-4 bg-amber-50 rounded-xl border border-amber-100">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1"
                        class="rounded text-amber-500 focus:ring-amber-500">
                    <label for="is_featured" class="text-sm font-bold text-amber-900">Feature this image on the gallery
                        page?</label>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="submit" class="btn btn-primary flex-1 justify-center py-4">
                        <i class="fas fa-cloud-upload-alt mr-2"></i> Upload to Gallery
                    </button>
                    <a href="{{ route('admin.gallery.index') }}" class="btn btn-secondary px-8">
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
                        preview.classList.remove('border-dashed');
                        preview.classList.add('border-solid');
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>
    @endsection
@endsection