<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Customer;
use App\Services\FonnteService;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    protected $fonnteService;

    public function __construct(FonnteService $fonnteService)
    {
        $this->middleware('role:super-admin|admin|sales');
        $this->fonnteService = $fonnteService;
    }

    public function index(Request $request)
    {
        $query = Message::with(['customer', 'user'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('channel')) {
            $query->where('channel', $request->channel);
        }

        if ($request->filled('direction')) {
            $query->where('direction', $request->direction);
        }

        $messages = $query->paginate(25);

        $stats = [
            'total' => Message::count('*'),
            'today' => Message::whereDate('created_at', '=', today())->count('*'),
            'inbound' => Message::where('direction', '=', 'inbound')->whereDate('created_at', '=', today())->count('*'),
            'outbound' => Message::where('direction', '=', 'outbound')->whereDate('created_at', '=', today())->count('*'),
        ];

        $customers = Customer::where('status', 'active')->orderBy('name')->get(['id', 'name']);

        return view('support.messages.index', compact('messages', 'stats', 'customers'));
    }

    public function create()
    {
        $customers = Customer::where('status', 'active')->orderBy('name')->get(['id', 'name', 'phone']);
        return view('support.messages.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'channel' => 'required|in:whatsapp,sms,email,web',
            'content' => 'required|string',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['direction'] = 'outbound';
        $validated['status'] = 'sent';

        Message::create($validated);

        // Send via Fonnte if channel is whatsapp
        if ($validated['channel'] === 'whatsapp') {
            $customer = Customer::find($request->customer_id);
            if ($customer && $customer->phone) {
                $this->fonnteService->sendMessage($customer->phone, $validated['content']);
            }
        }

        return redirect()->route('messages.index')
            ->with('success', 'Pesan berhasil dikirim!');
    }

    public function show(Customer $customer)
    {
        $messages = Message::where('customer_id', $customer->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('support.messages.show', compact('customer', 'messages'));
    }

    public function sendQuick(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'content' => 'required|string',
            'channel' => 'required|in:whatsapp,sms,email,web',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['direction'] = 'outbound';
        $validated['status'] = 'sent';

        Message::create($validated);

        // Send via Fonnte if channel is whatsapp
        if ($validated['channel'] === 'whatsapp') {
            $customer = Customer::find($request->customer_id);
            if ($customer && $customer->phone) {
                $this->fonnteService->sendMessage($customer->phone, $validated['content']);
            }
        }

        return back()->with('success', 'Pesan terkirim!');
    }
}
