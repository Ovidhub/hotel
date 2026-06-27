<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogPostRequest;
use App\Models\BlogPost;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::orderByDesc('published_at')->get();

        return view('admin.blog.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.blog.create');
    }

    public function store(BlogPostRequest $request)
    {
        $data = $this->buildData($request->validated(), null);
        BlogPost::create($data);

        return redirect()->route('admin.blog.index')
                         ->with('status', 'Blog post created successfully.');
    }

    public function edit(BlogPost $blog)
    {
        return view('admin.blog.edit', ['post' => $blog]);
    }

    public function update(BlogPostRequest $request, BlogPost $blog)
    {
        $data = $this->buildData($request->validated(), $blog);
        $blog->update($data);

        return redirect()->route('admin.blog.index')
                         ->with('status', 'Blog post updated successfully.');
    }

    public function destroy(BlogPost $blog)
    {
        $blog->delete();

        return redirect()->route('admin.blog.index')
                         ->with('status', 'Blog post deleted successfully.');
    }

    private function buildData(array $validated, ?BlogPost $post): array
    {
        // Derive slug from title if not provided
        if (empty($validated['slug'])) {
            $base = Str::slug($validated['title']);
            $slug = $base;
            $i    = 1;
            while (
                BlogPost::where('slug', $slug)
                    ->when($post, fn ($q) => $q->where('id', '!=', $post->id))
                    ->exists()
            ) {
                $slug = $base . '-' . $i++;
            }
        } else {
            $slug = $validated['slug'];
        }

        return [
            'slug'           => $slug,
            'title'          => $validated['title'],
            'excerpt'        => $validated['excerpt'],
            'body'           => $validated['body'],
            'category'       => $validated['category'],
            'category_color' => $validated['category_color'] ?? null,
            'image'          => $validated['image'],
            'author'         => $validated['author'],
            'published_at'   => !empty($validated['published_at'])
                                    ? $validated['published_at']
                                    : now(),
        ];
    }
}
