'use client';

import { InvoiceDashboard } from './components/InvoiceDashboard';

export default function FinancePage() {
  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-3xl font-bold tracking-tight">Manajemen Finansial</h1>
        <p className="text-zinc-400">Kelola tagihan pelanggan dan pengeluaran operasional ISP.</p>
      </div>
      <InvoiceDashboard />
    </div>
  );
}
