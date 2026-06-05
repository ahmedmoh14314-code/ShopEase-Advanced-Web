<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InfoPage;
use Illuminate\Http\JsonResponse;

class InfoPageController extends Controller
{
    public function index(): JsonResponse
    {
        $pages = InfoPage::where('is_published', true)
            ->orderBy('title')
            ->get(['id', 'title', 'slug']);

        return response()->json([
            'pages' => $pages,
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $page = InfoPage::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return response()->json([
            'page' => $page,
        ]);
    }
}
