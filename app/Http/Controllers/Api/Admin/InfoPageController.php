<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreInfoPageRequest;
use App\Http\Requests\Admin\UpdateInfoPageRequest;
use App\Models\InfoPage;

// Admin-only CRUD for informational static pages (about, privacy, terms, ...).
class InfoPageController extends Controller
{
    public function index()
    {
        $pages = InfoPage::orderBy('title')->get();

        return response()->json([
            'pages' => $pages,
        ]);
    }

    public function store(StoreInfoPageRequest $request)
    {
        $data = $request->validated();
        $data['is_published'] = $request->boolean('is_published', true);

        $page = InfoPage::create($data);

        return response()->json([
            'message' => 'Page created.',
            'page'    => $page,
        ], 201);
    }

    public function show(InfoPage $page)
    {
        return response()->json([
            'page' => $page,
        ]);
    }

    public function update(UpdateInfoPageRequest $request, InfoPage $page)
    {
        $data = $request->validated();
        $data['is_published'] = $request->boolean('is_published', $page->is_published);

        $page->update($data);

        return response()->json([
            'message' => 'Page updated.',
            'page'    => $page,
        ]);
    }

    public function destroy(InfoPage $page)
    {
        $page->delete();

        return response()->json(['message' => 'Page deleted.']);
    }
}
