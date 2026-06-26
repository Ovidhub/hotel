<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::where('published_at', '<=', now())->orderByDesc('published_at')->get();

        return view('blog.index', compact('posts'));
    }

    public function show(BlogPost $post)
    {
        return view('blog.show', compact('post'));
    }
}
