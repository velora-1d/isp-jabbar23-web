'use client';

import { useMarketing } from '@/hooks/use-marketing';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Loader2 } from 'lucide-react';

export function ReferralList() {
    const { referrals, isLoadingReferrals, payoutReferral } = useMarketing();

    if (isLoadingReferrals) {
        return <div className="flex items-center justify-center py-20 bg-zinc-900/20 rounded-2xl border border-zinc-800">
            <Loader2 className="h-8 w-8 animate-spin text-blue-500" />
        </div>;
    }

    if (referrals.length === 0) {
        return (
            <div className="flex flex-col items-center justify-center py-20 text-zinc-500 bg-zinc-900/20 rounded-2xl border border-dashed border-zinc-800">
                <p>Belum ada data referral.</p>
            </div>
        );
    }

    return (
        <Card className="bg-zinc-900/30 border-zinc-800/50">
            <CardContent className="p-0">
                <div className="overflow-x-auto">
                    <table className="w-full text-left">
                        <thead className="bg-zinc-900/50 text-xs font-semibold text-zinc-500 uppercase">
                            <tr>
                                <th className="px-6 py-4">Kode</th>
                                <th className="px-6 py-4">Referrer</th>
                                <th className="px-6 py-4">Referred</th>
                                <th className="px-6 py-4">Reward</th>
                                <th className="px-6 py-4">Status</th>
                                <th className="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-zinc-800">
                            {referrals.map((ref: any) => (
                                <tr key={ref.id} className="hover:bg-zinc-900/40 transition-colors">
                                    <td className="px-6 py-4 font-mono text-sm text-blue-400">{ref.code}</td>
                                    <td className="px-6 py-4">
                                        <div className="text-sm font-medium">{ref.referrer?.name}</div>
                                        <div className="text-[10px] text-zinc-500">{ref.referrer?.customer_id}</div>
                                    </td>
                                    <td className="px-6 py-4">
                                        {ref.referred ? (
                                            <>
                                                <div className="text-sm font-medium">{ref.referred.name}</div>
                                                <div className="text-[10px] text-zinc-500">{ref.referred.customer_id}</div>
                                            </>
                                        ) : (
                                            <span className="text-xs text-zinc-600">Belum terpakai</span>
                                        )}
                                    </td>
                                    <td className="px-6 py-4 text-sm font-bold">
                                        Rp {new Intl.NumberFormat('id-ID').format(ref.reward_amount)}
                                    </td>
                                    <td className="px-6 py-4">
                                        <Badge variant="outline" className="text-[10px] uppercase">
                                            {ref.status_label}
                                        </Badge>
                                    </td>
                                    <td className="px-6 py-4 text-right">
                                        {ref.status === 'qualified' && !ref.reward_paid && (
                                            <Button 
                                                size="sm" 
                                                className="bg-emerald-600 hover:bg-emerald-500 h-8 rounded-lg"
                                                onClick={() => payoutReferral.mutate(ref.id)}
                                                disabled={payoutReferral.isPending}
                                            >
                                                {payoutReferral.isPending ? <Loader2 className="h-3 w-3 animate-spin" /> : 'Cairkan'}
                                            </Button>
                                        )}
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </CardContent>
        </Card>
    );
}
