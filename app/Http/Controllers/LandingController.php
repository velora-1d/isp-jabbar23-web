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

    public function coverage(Request $request)
    {
        $query = $request->input('q');
        $results = [];

        if ($query) {
            // Search ODPs by location name, district, or village
            // Assuming ODP model has 'name', 'location_description', 'distict' etc.
            // Adjust column names based on actual ODP table structure
            $results = \App\Models\Network\Odp::where('name', 'like', "%{$query}%")
                ->orWhere('location_description', 'like', "%{$query}%")
                ->get();
        }

        return view('landing.coverage', compact('results', 'query'));
    }

    public function contact()
    {
        return view('landing.contact');
    }
}
