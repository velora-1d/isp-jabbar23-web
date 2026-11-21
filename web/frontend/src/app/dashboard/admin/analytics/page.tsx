'use client';

import { DashboardPageShell } from '@/components/dashboard/page-shell';
import { AnalyticsDashboard } from './components/AnalyticsDashboard';
import { BarChart3 } from 'lucide-react';

export default function AnalyticsPage() {
  return (
    <DashboardPageShell
      title="Business Intelligence"
      description="Analitik performa jaringan, keuangan, dan SDM secara real-time."
    >
      <AnalyticsDashboard />
    </DashboardPageShell>
  );
}
