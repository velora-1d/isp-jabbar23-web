'use client';

import { AnalyticsDashboard } from './components/AnalyticsDashboard';

export default function AnalyticsPage() {
  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold tracking-tight bg-gradient-to-r from-white to-zinc-400 bg-clip-text text-transparent">
            Business Intelligence
          </h1>
          <p className="text-zinc-400">Analitik performa jaringan, keuangan, dan SDM secara real-time.</p>
        </div>
      </div>
      <AnalyticsDashboard />
    </div>
  );
}
