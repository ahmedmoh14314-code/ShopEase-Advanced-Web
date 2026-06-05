<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFaqRequest;
use App\Http\Requests\Admin\UpdateFaqRequest;
use App\Models\Faq;

// Admin/manager CRUD for frequently asked questions.
class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return response()->json([
            'faqs' => $faqs,
        ]);
    }

    public function store(StoreFaqRequest $request)
    {
        $data = $request->validated();
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active', true);

        $faq = Faq::create($data);

        return response()->json([
            'message' => 'FAQ created.',
            'faq'     => $faq,
        ], 201);
    }

    public function show(Faq $faq)
    {
        return response()->json([
            'faq' => $faq,
        ]);
    }

    public function update(UpdateFaqRequest $request, Faq $faq)
    {
        $data = $request->validated();
        $data['sort_order'] = $data['sort_order'] ?? $faq->sort_order;
        $data['is_active'] = $request->boolean('is_active', $faq->is_active);

        $faq->update($data);

        return response()->json([
            'message' => 'FAQ updated.',
            'faq'     => $faq,
        ]);
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return response()->json(['message' => 'FAQ deleted.']);
    }
}
