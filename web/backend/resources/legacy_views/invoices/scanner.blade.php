@extends('layouts.app')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="py-6 max-w-2xl mx-auto">
        
        <div class="mb-6">
            <a href="{{ route('invoices.index') }}" class="text-gray-400 hover:text-white flex items-center transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Billing
            </a>
            <h1 class="text-2xl font-bold text-white mt-2">QR Scanner - Pembayaran Cash</h1>
            <p class="text-gray-400 text-sm mt-1">Scan QR Code customer untuk proses pembayaran cash</p>
        </div>

        <!-- Scanner Container -->
        <div class="bg-gray-800 rounded-2xl shadow-xl border border-gray-700/50 p-6 mb-6">
            <div id="reader" class="rounded-xl overflow-hidden"></div>
            <div id="scanner-status" class="text-center text-gray-400 mt-4">
                <svg class="w-8 h-8 mx-auto mb-2 text-teal-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                Arahkan kamera ke QR Code pelanggan...
            </div>
        </div>

        <!-- Result Container (Hidden by default) -->
        <div id="result-container" class="hidden">
            <!-- Customer Info -->
            <div class="bg-gray-800 rounded-2xl shadow-xl border border-gray-700/50 p-6 mb-6">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Customer Ditemukan
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500">ID Customer</p>
                        <p id="cust-id" class="text-white font-medium font-mono">-</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Nama</p>
                        <p id="cust-name" class="text-white font-semibold">-</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-xs text-gray-500">No. HP</p>
                        <p id="cust-phone" class="text-white font-medium">-</p>
                    </div>
                </div>
            </div>

            <!-- Unpaid Invoices -->
            <div class="bg-gray-800 rounded-2xl shadow-xl border border-gray-700/50 p-6 mb-6">
                <h3 class="text-lg font-bold text-white mb-4">Tagihan Belum Lunas</h3>
                <div id="invoices-list" class="space-y-3">
                    <!-- Invoices will be populated here -->
                </div>
                <div id="no-invoices" class="hidden text-center py-6 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Tidak ada tagihan yang belum dibayar 🎉
                </div>
            </div>

            <!-- Scan Again Button -->
            <button id="scan-again" class="w-full py-3 px-4 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-xl transition-colors flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Scan QR Lagi
            </button>
        </div>

        <!-- Success Message (Hidden by default) -->
        <div id="success-message" class="hidden bg-emerald-900/30 border border-emerald-500/30 rounded-2xl p-6 text-center">
            <svg class="w-16 h-16 mx-auto text-emerald-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <h3 class="text-xl font-bold text-emerald-400 mb-2">Pembayaran Berhasil!</h3>
            <p id="success-text" class="text-gray-300">Invoice sudah ditandai LUNAS</p>
            <button id="scan-new" class="mt-6 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-colors">
                Scan Customer Baru
            </button>
        </div>

    </div>
</div>

<!-- html5-qrcode library -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
    const csrfToken = '{{ csrf_token() }}';
    let html5QrCode = null;

    // Initialize scanner on page load
    document.addEventListener('DOMContentLoaded', function() {
        startScanner();
    });

    function startScanner() {
        document.getElementById('reader').innerHTML = '';
        document.getElementById('scanner-status').classList.remove('hidden');
        document.getElementById('result-container').classList.add('hidden');
        document.getElementById('success-message').classList.add('hidden');

        html5QrCode = new Html5Qrcode("reader");
        
        html5QrCode.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: { width: 250, height: 250 } },
            onScanSuccess,
            onScanFailure
        ).catch(err => {
            document.getElementById('scanner-status').innerHTML = `
                <svg class="w-8 h-8 mx-auto mb-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <p class="text-red-400">Tidak dapat mengakses kamera. Pastikan izin kamera diberikan.</p>
            `;
        });
    }

    function onScanSuccess(decodedText, decodedResult) {
        // Stop scanner
        html5QrCode.stop().then(() => {
            document.getElementById('scanner-status').classList.add('hidden');
            lookupCustomer(decodedText);
        });
    }

    function onScanFailure(error) {
        // Ignore scan failures (continuous scanning)
    }

    function lookupCustomer(token) {
        fetch('{{ route("invoices.lookupByToken") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ token: token })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showCustomerResult(data.customer, data.invoices);
            } else {
                alert(data.message || 'Customer tidak ditemukan');
                startScanner();
            }
        })
        .catch(err => {
            alert('Terjadi kesalahan: ' + err.message);
            startScanner();
        });
    }

    function showCustomerResult(customer, invoices) {
        document.getElementById('result-container').classList.remove('hidden');
        document.getElementById('cust-id').textContent = customer.customer_id;
        document.getElementById('cust-name').textContent = customer.name;
        document.getElementById('cust-phone').textContent = customer.phone || '-';

        const invoicesList = document.getElementById('invoices-list');
        const noInvoices = document.getElementById('no-invoices');
        invoicesList.innerHTML = '';

        if (invoices.length === 0) {
            noInvoices.classList.remove('hidden');
        } else {
            noInvoices.classList.add('hidden');
            invoices.forEach(inv => {
                invoicesList.innerHTML += `
                    <div class="flex justify-between items-center p-4 bg-gray-900/50 rounded-xl border border-gray-700/50">
                        <div>
                            <p class="text-white font-medium">${inv.invoice_number}</p>
                            <p class="text-xs text-gray-500">${inv.period} • Jatuh tempo: ${inv.due_date}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-indigo-400 font-bold">${inv.formatted_amount}</p>
                            <button onclick="payInvoice(${inv.id})" class="mt-1 px-3 py-1 text-xs bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors">
                                Bayar
                            </button>
                        </div>
                    </div>
                `;
            });
        }
    }

    function payInvoice(invoiceId) {
        if (!confirm('Konfirmasi pembayaran cash untuk invoice ini?')) return;

        fetch('{{ route("invoices.payByToken") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ invoice_id: invoiceId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('result-container').classList.add('hidden');
                document.getElementById('success-message').classList.remove('hidden');
                document.getElementById('success-text').textContent = data.message;
            } else {
                alert(data.message || 'Gagal memproses pembayaran');
            }
        })
        .catch(err => {
            alert('Terjadi kesalahan: ' + err.message);
        });
    }

    // Scan again buttons
    document.getElementById('scan-again').addEventListener('click', startScanner);
    document.getElementById('scan-new').addEventListener('click', startScanner);
</script>
@endsection
