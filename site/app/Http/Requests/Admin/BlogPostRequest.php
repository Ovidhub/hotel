<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BlogPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $postId = $this->route('blog')?->id;

        return [
            'title'          => ['required', 'string', 'max:255'],
            'slug'           => ['nullable', 'string', 'max:255', Rule::unique('blog_posts', 'slug')->ignore($postId)],
            'excerpt'        => ['required', 'string'],
            'body'           => ['required', 'string'],
            'category'       => ['required', 'string', 'max:255'],
            'category_color' => ['nullable', 'string', 'max:50'],
            'image'          => ['required', 'string', 'max:1000'],
            'author'         => ['required', 'string', 'max:255'],
            'published_at'   => ['nullable', 'date'],
        ];
    }
}
