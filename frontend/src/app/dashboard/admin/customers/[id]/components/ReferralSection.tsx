'use client';

import { useReferrals } from '@/hooks/use-referrals';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Gift, Users, CheckCircle2, Clock, Landmark } from 'lucide-react';
import { format } from 'date-fns';

interface ReferralSectionProps {
  customerId: number;
}

export function ReferralSection({ customerId }: ReferralSectionProps) {
  const { referrals, isLoading, payReward } = useReferrals({ referrer_id: customerId });

  if (isLoading) return <div>Memuat data rujukan...</div>;

  return (
    <div className="space-y-6">
      <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
        <Card className="bg-zinc-900/40 border-zinc-800 flex items-center p-4 gap-4">
            <div className="h-10 w-10 rounded-full bg-blue-500/10 flex items-center justify-center text-blue-400">
                <Users className="w-5 h-5" />
            </div>
            <div>
                <p className="text-[10px] uppercase text-zinc-500 font-bold">Total Rujukan</p>
                <p className="text-xl font-bold">{referrals?.length || 0}</p>
            </div>
        </Card>
        <Card className="bg-zinc-900/40 border-zinc-800 flex items-center p-4 gap-4">
            <div className="h-10 w-10 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-400">
                <CheckCircle2 className="w-5 h-5" />
            </div>
            <div>
                <p className="text-[10px] uppercase text-zinc-500 font-bold">Qualified</p>
                <p className="text-xl font-bold">{referrals?.filter(r => r.status === 'qualified' || r.status === 'rewarded').length || 0}</p>
            </div>
        </Card>
        <Card className="bg-zinc-900/40 border-zinc-800 flex items-center p-4 gap-4">
            <div className="h-10 w-10 rounded-full bg-amber-500/10 flex items-center justify-center text-amber-400">
                <Gift className="w-5 h-5" />
            </div>
            <div>
                <p className="text-[10px] uppercase text-zinc-500 font-bold">Total Reward</p>
                <p className="text-xl font-bold">Rp {referrals?.reduce((acc, r) => acc + r.reward_amount, 0).toLocaleString() || 0}</p>
            </div>
        </Card>
      </div>

      <Card className="bg-zinc-900/20 border-zinc-800">
        <CardHeader>
          <CardTitle className="text-base flex items-center gap-2">
            <Gift className="w-4 h-4 text-amber-400" />
            Daftar Pelanggan yang Dirujuk
          </CardTitle>
          <CardDescription>Pelanggan yang mendaftar menggunakan kode unik user ini.</CardDescription>
        </CardHeader>
        <CardContent>
          {referrals && referrals.length > 0 ? (
            <div className="space-y-4">
              {referrals.map((referral) => (
                <div key={referral.id} className="flex items-center justify-between p-4 bg-zinc-950/50 border border-zinc-800 rounded-xl">
                  <div className="flex items-center gap-4">
                    <div className="h-10 w-10 rounded-full bg-zinc-800 flex items-center justify-center text-xs font-bold">
                       {referral.referred?.name.substring(0, 2).toUpperCase() || '??'}
                    </div>
                    <div>
                      <p className="font-medium text-sm">{referral.referred?.name || 'Pelanggan Baru'}</p>
                      <p className="text-[10px] text-zinc-500">Terdaftar: {format(new Date(referral.created_at), 'dd MMM yyyy')}</p>
                    </div>
                  </div>
                  
                  <div className="flex items-center gap-6">
                    <div className="text-right">
                       <Badge variant={referral.status === 'rewarded' ? 'default' : 'outline'} className={
                         referral.status === 'qualified' ? 'border-emerald-500/50 text-emerald-400' : ''
                       }>
                         {referral.status.toUpperCase()}
                       </Badge>
                       <p className="text-[11px] mt-1 font-bold">Rp {referral.reward_amount.toLocaleString()}</p>
                    </div>

                    {referral.status === 'qualified' && !referral.reward_paid && (
                      <Button 
                        size="sm" 
                        variant="secondary"
                        onClick={() => payReward.mutate(referral.id)}
                        disabled={payReward.isPending}
                      >
                        <Landmark className="w-3.5 h-3.5 mr-2" />
                        Bayar Reward
                      </Button>
                    )}
                  </div>
                </div>
              ))}
            </div>
          ) : (
            <div className="py-12 text-center text-zinc-500 text-sm italic">
                Belum ada rujukan yang tercatat untuk pelanggan ini.
            </div>
          )}
        </CardContent>
      </Card>
    </div>
  );
}
