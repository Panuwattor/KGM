<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('postCategory', 'author')->withTrashed()->latest()->paginate(20);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = PostCategory::all();
        return view('admin.posts.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validatePost($request);
        $data['slug'] = Str::slug($request->title) . '-' . Str::random(5);
        $data['author_id'] = auth()->id();
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        }
        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }
        Post::create($data);
        return redirect()->route('admin.posts.index')->with('success', 'สร้างบทความแล้ว');
    }

    public function edit(Post $post)
    {
        $categories = PostCategory::all();
        return view('admin.posts.form', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        $data = $this->validatePost($request);
        if ($request->hasFile('featured_image')) {
            \Storage::disk('public')->delete($post->featured_image);
            $data['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        }
        if ($data['status'] === 'published' && !$post->published_at) {
            $data['published_at'] = now();
        }
        $post->update($data);
        return redirect()->route('admin.posts.index')->with('success', 'อัปเดตบทความแล้ว');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'ลบบทความแล้ว');
    }

    private function validatePost(Request $request): array
    {
        return $request->validate([
            'post_category_id' => 'nullable|exists:post_categories,id',
            'title'            => 'required|string|max:255',
            'excerpt'          => 'nullable|string|max:500',
            'body'             => 'required|string',
            'featured_image'   => 'nullable|image|max:5120',
            'status'           => 'required|in:draft,published,scheduled',
            'published_at'     => 'nullable|date',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);
    }
}
