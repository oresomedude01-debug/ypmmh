@extends('layouts.dashboard')

@section('title', 'Create New Blog Post (Admin)')

@section('styles')
    <!-- Quill editor styles -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        #editor {
            height: 400px;
            background: white;
            border-radius: 0.5rem;
        }

        .ql-toolbar.ql-snow {
            border-radius: 0.5rem 0.5rem 0 0;
            border-color: var(--border-color);
        }

        .ql-container.ql-snow {
            border-radius: 0 0 0.5rem 0.5rem;
            border-color: var(--border-color);
            font-family: inherit;
        }
    </style>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.blogs.index') }}"
                class="p-2 rounded-lg hover:bg-slate-100 transition-all text-slate-500">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold">Create New Post</h2>
                <p class="text-slate-500 text-sm">Write an engaging article as an Administrator.</p>
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

        <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data" id="blogForm">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left: Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="glass p-6 rounded-xl space-y-4">
                        <div>
                            <label for="title" class="block text-sm font-bold text-slate-700 mb-1">Post Title</label>
                            <input type="text" name="title" id="title" placeholder="e.g. 5 Tips for Islamic Parenting"
                                class="w-full" value="{{ old('title') }}" required>
                        </div>

                        <div>
                            <label for="excerpt" class="block text-sm font-bold text-slate-700 mb-1">Short Excerpt</label>
                            <textarea name="excerpt" id="excerpt" rows="3" placeholder="A brief summary..."
                                class="w-full">{{ old('excerpt') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Content</label>
                            <div id="editor"></div>
                            <input type="hidden" name="content" id="content">
                        </div>
                    </div>
                </div>

                <!-- Right: Settings -->
                <div class="space-y-6">
                    <div class="glass p-6 rounded-xl space-y-4">
                        <h3 class="font-bold text-slate-900 border-b border-slate-100 pb-3">Publication Stats</h3>

                        <div>
                            <label for="status" class="block text-sm font-bold text-slate-700 mb-1">Status</label>
                            <select name="status" id="status" class="w-full">
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published
                                </option>
                            </select>
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-bold text-slate-700 mb-1">Category</label>
                            <select name="category" id="category" class="w-full">
                                <option value="General">General</option>
                                <option value="Psychology">Psychology</option>
                                <option value="Education">Education</option>
                                <option value="Parenting">Parenting</option>
                                <option value="Spiritual">Spiritual</option>
                                <option value="Leadership">Leadership</option>
                            </select>
                        </div>

                        <div>
                            <label for="featured_image" class="block text-sm font-bold text-slate-700 mb-1">Featured
                                Image</label>
                            <div class="relative group cursor-pointer">
                                <input type="file" name="featured_image" id="featured_image" class="hidden" accept="image/*"
                                    onchange="previewImage(this)">
                                <div id="image-preview"
                                    class="w-full aspect-video rounded-xl bg-slate-50 border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400 group-hover:bg-slate-100 group-hover:border-blue-300 transition-all overflow-hidden"
                                    onclick="document.getElementById('featured_image').click()">
                                    <i class="fas fa-cloud-upload-alt text-2xl mb-2"></i>
                                    <span class="text-[10px] font-bold uppercase tracking-wider">Click to upload</span>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-full justify-center mt-6">
                            <i class="fas fa-save"></i>
                            Save Post
                        </button>
                        <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary w-full justify-center">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        var quill = new Quill('#editor', {
            theme: 'snow',
            placeholder: 'Write your story here...',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    ['link', 'image'],
                    ['clean']
                ]
            }
        });

        var form = document.getElementById('blogForm');
        form.onsubmit = function () {
            var content = document.querySelector('input[name=content]');
            content.value = quill.root.innerHTML;
            return true;
        };

        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    const preview = document.getElementById('image-preview');
                    preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                    preview.classList.remove('p-8');
                    preview.classList.add('border-solid');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection