<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FaqRequest;
use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::orderBy('sort')->orderBy('id')->get();

        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        return view('admin.faqs.create');
    }

    public function store(FaqRequest $request)
    {
        $data = $request->validated();
        $data['sort'] = (int) ($data['sort'] ?? 0);
        Faq::create($data);

        return redirect()->route('admin.faqs.index')
                         ->with('status', 'FAQ created successfully.');
    }

    public function edit(Faq $faq)
    {
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(FaqRequest $request, Faq $faq)
    {
        $data = $request->validated();
        $data['sort'] = (int) ($data['sort'] ?? 0);
        $faq->update($data);

        return redirect()->route('admin.faqs.index')
                         ->with('status', 'FAQ updated successfully.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()->route('admin.faqs.index')
                         ->with('status', 'FAQ deleted successfully.');
    }
}
