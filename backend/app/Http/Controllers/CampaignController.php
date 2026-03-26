<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Traits\HasFilters;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    use HasFilters;

    public function __construct()
    {
        $this->middleware('role:super-admin|admin|sales-cs');
    }

    public function index(Request $request): View
    {
        $query = Campaign::with(['creator']);

        // Apply global filters
        $this->applyGlobalFilters($query, $request, [
            'dateColumn' => 'created_at',
            'searchColumns' => ['name', 'description']
        ]);

        // Apply status filter
        $this->applyStatusFilter($query, $request);

        // Apply type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $campaigns = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => Campaign::count(),
            'running' => Campaign::where('status', 'running')->count(),
            'completed' => Campaign::where('status', 'completed')->count(),
            'total_sent' => Campaign::sum('sent_count'),
        ];

        // Filter options
        $statuses = [
            'draft' => 'Draft',
            'scheduled' => 'Scheduled',
            'running' => 'Running',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ];

        $types = [
            'email' => 'Email',
            'whatsapp' => 'WhatsApp',
            'sms' => 'SMS',
            'push' => 'Push Notification',
        ];

        return view('marketing.campaigns.index', compact('campaigns', 'stats', 'statuses', 'types'));
    }

    public function create(): View
    {
        return view('marketing.campaigns.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:email,whatsapp,sms,push',
            'message_template' => 'required|string',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['status'] = $request->filled('scheduled_at') ? 'scheduled' : 'draft';

        Campaign::create($validated);

        return redirect()->route('campaigns.index')
            ->with('success', 'Campaign berhasil dibuat!');
    }

    public function show(Campaign $campaign): View
    {
        $campaign->load(['creator']);
        return view('marketing.campaigns.show', compact('campaign'));
    }

    public function edit(Campaign $campaign): View
    {
        return view('marketing.campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, Campaign $campaign): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:email,whatsapp,sms,push',
            'message_template' => 'required|string',
            'scheduled_at' => 'nullable|date',
        ]);

        $campaign->update($validated);

        return redirect()->route('campaigns.index')
            ->with('success', 'Campaign berhasil diperbarui!');
    }

    public function destroy(Campaign $campaign): RedirectResponse
    {
        Campaign::destroy($campaign->getKey());

        return redirect()->route('campaigns.index')
            ->with('success', 'Campaign berhasil dihapus!');
    }

    public function launch(Campaign $campaign): RedirectResponse
    {
        $campaign->update([
            'status' => 'running',
            'started_at' => now(),
        ]);

        // TODO: Dispatch job to send messages

        return back()->with('success', 'Campaign berhasil diluncurkan!');
    }

    public function cancel(Campaign $campaign): RedirectResponse
    {
        $campaign->update(['status' => 'cancelled']);

        return back()->with('success', 'Campaign dibatalkan!');
    }
}
