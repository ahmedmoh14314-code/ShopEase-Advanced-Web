<?php

namespace App\Http\Requests\Admin;

use App\Models\Subcategory;
use Closure;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|integer|exists:categories,id',
            'subcategory_id' => ['nullable', 'integer', 'exists:subcategories,id', $this->subcategoryBelongsToCategory()],
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'is_featured' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
        ];
    }

    // subcategory must actually belong to the chosen category
    private function subcategoryBelongsToCategory(): Closure
    {
        return function ($attribute, $value, $fail) {
            if (! $value) {
                return;
            }

            $categoryId = $this->input('category_id');
            if (! $categoryId) {
                return;
            }

            $belongs = Subcategory::where('id', $value)
                ->where('category_id', $categoryId)
                ->exists();

            if (! $belongs) {
                $fail('The selected subcategory does not belong to the chosen category.');
            }
        };
    }
}
