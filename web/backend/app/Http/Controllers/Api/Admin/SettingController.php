<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    /**
     * Get settings by group.
     */
    public function index(Request $request): JsonResponse
    {
        $group = $request->query('group', 'general');
        $settings = Setting::where('group', $group)->get()->pluck('value', 'key');
        
        return response()->json($settings);
    }

    /**
     * Update multiple settings.
     */
    public function update(Request $request): JsonResponse
    {
        $data = $request->all();
        $group = $request->query('group', 'general');

        foreach ($data as $key => $value) {
            Setting::set($key, $value, $group);
        }

        return response()->json(['message' => 'Settings updated successfully']);
    }
}
