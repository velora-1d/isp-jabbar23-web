<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Customer;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        // Get active packages for display
        // We might want to filter or prioritize certain packages
        $packages = Package::where('is_active', true)
            ->orderBy('price', 'asc')
            ->take(3) // Take top 3 for the homepage display
            ->get();

        // Get some stats for the "Trust Badges" section
        $stats = [
            'customers' => Customer::count() + 1000, // Add base number for display
            'uptime' => '99.9%',
            'support' => '24/7'
        ];

        return view('welcome', compact('packages', 'stats'));
    }
}
