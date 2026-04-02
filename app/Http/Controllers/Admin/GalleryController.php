<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $images = GalleryImage::with('author')->latest()->paginate(12);
        return view('Admin.gallery.index', compact('images'));
    }

    public function create()
    {
        return view('Admin.gallery.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'status' => 'required|in:active,inactive',
            'is_featured' => 'boolean'
        ]);

        $path = $request->file('image')->store('gallery', 'public');

        GalleryImage::create([
            'title' => $request->title,
            'category' => $request->category,
            'image_path' => $path,
            'status' => $request->status,
            'is_featured' => $request->has('is_featured'),
            'author_id' => auth()->id(),
            'order_index' => 0
        ]);

        return redirect()->route('admin.gallery.index')->with('success', 'Image added to gallery successfully.');
    }

    public function edit(GalleryImage $gallery)
    {
        return view('Admin.gallery.edit', compact('gallery'));
    }

    public function update(Request $request, GalleryImage $gallery)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'status' => 'required|in:active,inactive',
            'is_featured' => 'boolean'
        ]);

        $data = [
            'title' => $request->title,
            'category' => $request->category,
            'status' => $request->status,
            'is_featured' => $request->has('is_featured'),
        ];

        if ($request->hasFile('image')) {
            if (!Str::startsWith($gallery->image_path, 'http')) {
                Storage::disk('public')->delete($gallery->image_path);
            }
            $data['image_path'] = $request->file('image')->store('gallery', 'public');
        }

        $gallery->update($data);

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery image updated successfully.');
    }

    public function destroy(GalleryImage $gallery)
    {
        if (!Str::startsWith($gallery->image_path, 'http')) {
            Storage::disk('public')->delete($gallery->image_path);
        }
        $gallery->delete();

        return redirect()->route('admin.gallery.index')->with('success', 'Image removed from gallery.');
    }
}
