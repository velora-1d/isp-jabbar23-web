<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use Illuminate\Http\Request;

class ApiManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:super-admin');
    }

    public function index()
    {
        $apiKeys = ApiKey::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => ApiKey::count(),
            'active' => ApiKey::where('is_active', true)->count(),
            'total_usage' => ApiKey::sum('usage_count'),
        ];

        return view('admin.api-management.index', compact('apiKeys', 'stats'));
    }

    public function create()
    {
        return view('admin.api-management.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['is_active'] = true;

        $apiKey = ApiKey::create($validated);

        return redirect()->route('api-management.index')
            ->with('success', 'API Key berhasil dibuat!')
            ->with('new_key', $apiKey->key);
    }

    public function toggleActive(ApiKey $apiKey)
    {
        $apiKey->update(['is_active' => !$apiKey->is_active]);
        return back()->with('success', 'Status API Key berhasil diubah!');
    }

    public function regenerate(ApiKey $apiKey)
    {
        $apiKey->update(['key' => 'jbr_' . \Illuminate\Support\Str::random(32)]);
        return back()->with('success', 'API Key berhasil di-regenerate!')->with('new_key', $apiKey->key);
    }

    public function destroy(ApiKey $apiKey)
    {
        $apiKey->delete();
        return redirect()->route('api-management.index')
            ->with('success', 'API Key berhasil dihapus!');
    }
}
