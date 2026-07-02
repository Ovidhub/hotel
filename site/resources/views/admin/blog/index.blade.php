<x-layouts.admin title="Blog Posts">

    <div class="flex items-center justify-between mb-5">
        <h2 class="text-lg font-semibold text-gray-800">Blog Posts</h2>
        <a href="{{ route('admin.blog.create') }}"
           class="inline-flex items-center gap-1.5 rounded-lg bg-[#7C0E52] px-4 py-2 text-sm font-medium text-white hover:bg-[#560A3A] transition-colors">
            + New Post
        </a>
    </div>

    <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50/60">
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Title</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 hidden md:table-cell">Category</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 hidden lg:table-cell">Published</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($posts as $post)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3.5">
                        <div class="font-medium text-gray-900">{{ $post->title }}</div>
                        <div class="text-xs text-gray-400">{{ $post->slug }}</div>
                    </td>
                    <td class="px-5 py-3.5 hidden md:table-cell">
                        @if($post->category_color)
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium text-white"
                                  style="background-color: {{ $post->category_color }}">
                                {{ $post->category }}
                            </span>
                        @else
                            <span class="text-gray-600">{{ $post->category }}</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 hidden lg:table-cell text-gray-500 text-xs">
                        @if($post->published_at && $post->published_at->isPast())
                            <span class="text-emerald-600 font-medium">{{ $post->published_at->format('d M Y H:i') }}</span>
                        @elseif($post->published_at)
                            <span class="text-amber-600 font-medium">Scheduled: {{ $post->published_at->format('d M Y H:i') }}</span>
                        @else
                            <span class="text-gray-400">Draft</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.blog.edit', $post) }}"
                               class="inline-flex items-center rounded-lg bg-[#C79A46]/10 px-3 py-1.5 text-xs font-medium text-[#C79A46] hover:bg-[#C79A46]/20 transition-colors">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.blog.destroy', $post) }}"
                                  onsubmit="return confirm('Delete this post? This cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center rounded-lg bg-red-50 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-100 transition-colors">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-5 py-12 text-center text-sm text-gray-400">
                        No blog posts yet. <a href="{{ route('admin.blog.create') }}" class="text-[#7C0E52] underline">Create one.</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-layouts.admin>
