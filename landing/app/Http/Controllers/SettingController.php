<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display the settings form.
     */
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('settings.index', compact('settings'));
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {
        // 1. Handle File Upload (Logo)
        if ($request->hasFile('app_logo')) {
            $request->validate([
                'app_logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            
            // Delete old logo if exists? Optional.
            
            $path = $request->file('app_logo')->store('public/logos');
            $url = Storage::url($path);
            
            Setting::updateOrCreate(
                ['key' => 'app_logo'],
                ['value' => $url, 'group' => 'general']
            );
        }

        // 2. Handle Text Inputs
        $inputKeys = [
            'app_name', 
            'app_address', 
            'company_phone',
            'company_email',
            'bank_account',
            'midtrans_server_key', 
            'midtrans_client_key', 
            'midtrans_merchant_id',
            'wa_api_url', 
            'wa_api_key'
        ];

        foreach ($inputKeys as $key) {
            // Only update if present in request (even if empty string)
            if ($request->has($key)) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $request->input($key), 'group' => 'general']
                );
            }
        }

        return redirect()->route('settings.index')->with('success', 'Pengaturan aplikasi berhasil diperbarui.');
    }
}
