@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Back Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('invoices.index') }}" class="p-2 rounded-lg bg-gray-800 text-gray-400 hover:bg-gray-700 hover:text-white transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-white">Invoice #{{ $invoice->invoice_number }}</h1>
            <p class="text-gray-400">Issued to {{ $invoice->customer->name }}</p>
        </div>
        <div class="ml-auto">
            @php
                $statusColor = match($invoice->status) {
                    'paid' => 'green',
                    'unpaid' => 'red',
                    'partial' => 'yellow',
                    'overdue' => 'orange',
                    'cancelled' => 'gray',
                    default => 'blue'
                };
            @endphp
            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-{{ $statusColor }}-500/20 text-{{ $statusColor }}-400 border border-{{ $statusColor }}-500/30 capitalize">
                {{ ucfirst($invoice->status) }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Main Invoice Details -->
        <div class="md:col-span-2 space-y-6">
            <!-- Invoice Details Card -->
            <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl p-6">
                <h3 class="text-lg font-bold text-white mb-4">Invoice Details</h3>
                
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <div class="text-sm text-gray-400 mb-1">Billing Period</div>
                        <div class="font-medium text-white">
                            {{ $invoice->period_start->format('d M') }} - {{ $invoice->period_end->format('d M Y') }}
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-400 mb-1">Due Date</div>
                        <div class="font-medium text-white {{ $invoice->due_date->isPast() && $invoice->status !== 'paid' ? 'text-red-400' : '' }}">
                            {{ $invoice->due_date->format('d M Y') }}
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-400 mb-1">Total Amount</div>
                        <div class="text-xl font-bold text-white">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-400 mb-1">Customer ID</div>
                        <div class="font-mono text-gray-300">{{ $invoice->customer->cid }}</div>
                    </div>
                </div>

                @if($invoice->status === 'paid')
                <div class="mt-6 p-4 bg-green-500/10 border border-green-500/20 rounded-lg flex items-center gap-3">
                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <div class="font-semibold text-green-400">Paid in Full</div>
                        <div class="text-sm text-green-400/80">
                            Paid on {{ $invoice->payment_date ? $invoice->payment_date->format('d M Y') : '-' }} via {{ ucfirst($invoice->payment_method) }}
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Payment History -->
            <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl p-6">
                <h3 class="text-lg font-bold text-white mb-4">Payment History</h3>
                
                @if($invoice->payments->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gray-700/30">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-400 uppercase">Date</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-400 uppercase">Ref #</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-400 uppercase">Method</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-400 uppercase">Amount</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-400 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach($invoice->payments as $payment)
                            <tr class="hover:bg-gray-700/20 transition">
                                <td class="px-4 py-3 text-sm text-white">{{ $payment->paid_at->format('d M Y H:i') }}</td>
                                <td class="px-4 py-3 text-sm text-gray-300 font-mono">{{ $payment->reference_number ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-300 capitalize">{{ str_replace('_', ' ', $payment->payment_method) }}</td>
                                <td class="px-4 py-3 text-right text-sm font-bold text-white">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right">
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-{{ $payment->status_color }}-500/20 text-{{ $payment->status_color }}-400 capitalize">
                                        {{ $payment->status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-8 text-gray-500">
                    No payments recorded yet.
                </div>
                @endif
            </div>
        </div>

        <!-- Action Panel -->
        <div class="space-y-6">
            @if($invoice->status !== 'paid' && $invoice->status !== 'cancelled')
            <!-- Make Payment Form -->
            <div class="bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-xl p-6 shadow-xl">
                <h3 class="text-lg font-bold text-white mb-4">Record Payment</h3>
                
                @php
                    $paidAmount = $invoice->payments->where('status', 'confirmed')->sum('amount');
                    $remaining = $invoice->amount - $paidAmount;
                @endphp

                <form action="{{ route('payments.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Amount to Pay</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                            <input type="number" name="amount" value="{{ old('amount', $remaining) }}" max="{{ $remaining }}" class="w-full bg-gray-800 border border-gray-600 rounded-lg pl-10 pr-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        </div>
                        <div class="text-xs text-gray-500 mt-1">Remaining: Rp {{ number_format($remaining, 0, ',', '.') }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Payment Method</label>
                        <select name="payment_method" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="cash">Cash / Tunai</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="qris">QRIS</option>
                            <option value="ewallet">E-Wallet</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Reference / Note</label>
                        <input type="text" name="notes" placeholder="Optional notes" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>

                    <button type="submit" class="w-full py-2 bg-green-600 hover:bg-green-500 text-white font-bold rounded-lg transition shadow-lg shadow-green-600/20">
                        Process Manual Payment (Cash)
                    </button>
                </form>

                <div class="mt-4 pt-4 border-t border-gray-700">
                    <button id="pay-button" class="w-full py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-lg transition shadow-lg shadow-indigo-600/20 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        Pay Online (QRIS / VA)
                    </button>
                </div>
            </div>

            <!-- Midtrans Snap Script -->
            <script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
            <script type="text/javascript">
              document.getElementById('pay-button').onclick = function(){
                // SnapToken acquired from previous step
                fetch("{{ route('payment.create', $invoice) }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if(data.error) {
                        alert(data.error);
                        return;
                    }
                    snap.pay(data.snap_token, {
                      // Optional
                      onSuccess: function(result){
                        alert("payment success!"); 
                        location.reload();
                      },
                      onPending: function(result){
                        alert("wating your payment!"); 
                        location.reload();
                      },
                      onError: function(result){
                        alert("payment failed!"); 
                        location.reload();
                      }
                    });
                });
              };
            </script>
            @endif

            <!-- Customer Info Mini Card -->
            <div class="bg-gray-800/50 backdrop-blur border border-gray-700/50 rounded-xl p-4">
                <h4 class="text-sm font-bold text-gray-300 uppercase tracking-wider mb-2">Customer</h4>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold">
                        {{ substr($invoice->customer->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="font-medium text-white">{{ $invoice->customer->name }}</div>
                        <div class="text-xs text-gray-400">{{ $invoice->customer->email }}</div>
                    </div>
                </div>
                <div class="text-sm text-gray-400">
                    {{ $invoice->customer->address }}
                </div>
                <div class="mt-3 pt-3 border-t border-gray-700/50 flex justify-between items-center">
                    <span class="text-xs text-gray-500">Subscription</span>
                    <span class="text-xs text-blue-400 font-medium">{{ $invoice->customer->package->name ?? 'No Package' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
