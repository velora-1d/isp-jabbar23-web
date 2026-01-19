<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:super-admin|admin|sales');
    }

    public function index(): View
    {
        $campaigns = Campaign::query()
            ->with(['creator'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => Campaign::query()->count('*'),
            'running' => Campaign::query()->where('status', '=', 'running')->count('*'),
            'completed' => Campaign::query()->where('status', '=', 'completed')->count('*'),
            'total_sent' => Campaign::query()->sum('sent_count'),
        ];

        return view('marketing.campaigns.index', compact('campaigns', 'stats'));
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
        $campaign->delete();

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
