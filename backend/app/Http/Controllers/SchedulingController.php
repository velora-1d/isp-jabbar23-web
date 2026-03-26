<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkOrder;
use App\Models\User;

class SchedulingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Add permission check if needed later, e.g. permission:view scheduling
    }

    public function index()
    {
        return view('scheduling.index');
    }

    public function events(Request $request)
    {
        // Fetch Work Orders that have a scheduled date
        $query = WorkOrder::whereNotNull('scheduled_date')
            ->where('status', '!=', 'cancelled')
            ->with(['customer', 'technician']);

        if ($request->has('start') && $request->has('end')) {
            $query->whereBetween('scheduled_date', [$request->start, $request->end]);
        }

        $workOrders = $query->get();

        $events = $workOrders->map(function ($wo) {
            $color = match($wo->status) {
                'completed' => '#10B981', // green
                'in_progress' => '#3B82F6', // blue
                'pending' => '#F59E0B', // yellow/orange
                'scheduled' => '#8B5CF6', // purple
                default => '#6B7280', // gray
            };

            return [
                'id' => $wo->id,
                'title' => $wo->ticket_number . ' - ' . ($wo->customer ? $wo->customer->name : 'N/A'),
                'start' => $wo->scheduled_date->toIso8601String(),
                // 'end' => $wo->scheduled_date->addHours(2)->toIso8601String(), // Assuming 2 hour duration if not set
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'technician' => $wo->technician ? $wo->technician->name : 'Unassigned',
                    'type' => ucfirst($wo->type),
                    'address' => $wo->customer ? $wo->customer->address : '-',
                    'priority' => ucfirst($wo->priority),
                    'description' => $wo->description,
                ],
                'url' => route('work-orders.show', $wo->id),
            ];
        });

        return response()->json($events);
    }
}
