<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Models\ContactMessage;

// Public endpoint that stores messages from the storefront contact form.
class ContactController extends Controller
{
    public function store(StoreContactRequest $request)
    {
        ContactMessage::create($request->validated());

        return response()->json([
            'message' => 'Thanks! Your message has been received.',
        ], 201);
    }
}
