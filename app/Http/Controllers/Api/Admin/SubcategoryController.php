<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSubcategoryRequest;
use App\Http\Requests\Admin\UpdateSubcategoryRequest;
use App\Models\Subcategory;

// Admin/manager CRUD for subcategories, each linked to a parent category.
class SubcategoryController extends Controller
{
    public function index()
    {
        $subcategories = Subcategory::with('category')
            ->orderBy('name')
            ->get();

        return response()->json([
            'subcategories' => $subcategories,
        ]);
    }

    public function store(StoreSubcategoryRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        $subcategory = Subcategory::create($data);
        $subcategory->load('category');

        return response()->json([
            'message'     => 'Subcategory created.',
            'subcategory' => $subcategory,
        ], 201);
    }

    public function show(Subcategory $subcategory)
    {
        $subcategory->load('category');

        return response()->json([
            'subcategory' => $subcategory,
        ]);
    }

    public function update(UpdateSubcategoryRequest $request, Subcategory $subcategory)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', $subcategory->is_active);

        $subcategory->update($data);
        $subcategory->load('category');

        return response()->json([
            'message'     => 'Subcategory updated.',
            'subcategory' => $subcategory,
        ]);
    }

    public function destroy(Subcategory $subcategory)
    {
        $subcategory->delete();

        return response()->json(['message' => 'Subcategory deleted.']);
    }
}
