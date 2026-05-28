<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use App\Models\Setting;

// Admin-only site settings stored as key/value pairs.
class SettingController extends Controller
{
    // just the keys we expose to the admin UI
    private array $keys = [
        'site_name',
        'site_email',
        'site_phone',
        'site_address',
        'currency',
    ];

    public function index()
    {
        return response()->json([
            'settings' => $this->fetchSettings(),
        ]);
    }

    public function update(UpdateSettingsRequest $request)
    {
        foreach ($request->validated() as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return response()->json([
            'message'  => 'Settings updated.',
            'settings' => $this->fetchSettings(),
        ]);
    }

    private function fetchSettings(): array
    {
        $rows = Setting::whereIn('key', $this->keys)->pluck('value', 'key');

        $result = [];
        foreach ($this->keys as $key) {
            $result[$key] = $rows[$key] ?? null;
        }

        return $result;
    }
}
