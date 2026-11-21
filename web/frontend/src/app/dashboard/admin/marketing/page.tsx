'use client';

import { useState } from 'react';
import { 
    Megaphone, 
    Gift, 
    Users, 
    ArrowUpRight, 
    Plus, 
    Search, 
    Filter,
    Percent,
    Banknote,
    Calendar,
    ChevronRight,
    Copy,
    CheckCircle2
} from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';

import { useMarketing } from '@/hooks/use-marketing';
import { PromotionList } from '@/components/marketing/promotion-list';
import { ReferralList } from '@/components/marketing/referral-list';

export default function MarketingPage() {
    const [activeTab, setActiveTab] = useState('promotions');
    const { referralStats, promotions, referrals } = useMarketing();

    return (
        <div className="space-y-8 animate-in fade-in duration-700">
            {/* Header Section */}
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 className="text-3xl font-bold tracking-tight bg-gradient-to-r from-white to-zinc-400 bg-clip-text text-transparent">
                        Marketing & Growth
                    </h1>
                    <p className="text-zinc-500 mt-1">
                        Kelola kampanye promosi dan program referral pelanggan.
                    </p>
                </div>
                <div className="flex items-center gap-3">
                    <Button className="bg-blue-600 hover:bg-blue-500 text-white rounded-xl gap-2 shadow-lg shadow-blue-500/20">
                        <Plus className="h-4 w-4" />
                        Buat Promosi
                    </Button>
                </div>
            </div>

            {/* Bento Grid Stats */}
            <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                <Card className="md:col-span-2 bg-zinc-900/50 border-zinc-800/50 overflow-hidden relative group">
                    <div className="absolute inset-0 bg-gradient-to-br from-blue-600/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <CardHeader className="pb-2">
                        <CardDescription className="text-zinc-500 uppercase tracking-wider text-xs font-semibold">Total Promosi Aktif</CardDescription>
                        <CardTitle className="text-4xl font-black text-white">{promotions.filter((p:any) => p.is_active).length}</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div className="text-zinc-500 text-xs">Dari total {promotions.length} kampanye</div>
                    </CardContent>
                    <Megaphone className="absolute top-6 right-6 h-12 w-12 text-zinc-800 group-hover:text-blue-500/20 transition-colors duration-500" />
                </Card>

                <Card className="bg-zinc-900/50 border-zinc-800/50 overflow-hidden relative group">
                    <CardHeader className="pb-2">
                        <CardDescription className="text-zinc-500 uppercase tracking-wider text-xs font-semibold">Referral Sukses</CardDescription>
                        <CardTitle className="text-3xl font-bold text-white">{referralStats?.total_qualified || 0}</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div className="text-zinc-500 text-xs text-emerald-400">Total: {referrals.length} rujukan</div>
                    </CardContent>
                    <Users className="absolute bottom-4 right-4 h-8 w-8 text-zinc-800 group-hover:text-purple-500/20 transition-colors duration-500" />
                </Card>

                <Card className="bg-zinc-900/50 border-zinc-800/50 overflow-hidden relative group">
                    <CardHeader className="pb-2">
                        <CardDescription className="text-zinc-500 uppercase tracking-wider text-xs font-semibold">Reward Dibayarkan</CardDescription>
                        <CardTitle className="text-xl font-bold text-white">
                            Rp {new Intl.NumberFormat('id-ID').format(referralStats?.total_reward_paid || 0)}
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p className="text-xs text-zinc-500">Tertunda: Rp {new Intl.NumberFormat('id-ID').format(referralStats?.pending_rewards || 0)}</p>
                    </CardContent>
                </Card>
            </div>

            {/* Main Tabs Selection */}
            <Tabs defaultValue="promotions" onValueChange={setActiveTab} className="w-full">
                <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                    <TabsList className="bg-zinc-900/80 border border-zinc-800 p-1 rounded-xl">
                        <TabsTrigger value="promotions" className="rounded-lg gap-2 data-[state=active]:bg-zinc-800 data-[state=active]:text-blue-400">
                            <Percent className="h-4 w-4" />
                            Daftar Promosi
                        </TabsTrigger>
                        <TabsTrigger value="referrals" className="rounded-lg gap-2 data-[state=active]:bg-zinc-800 data-[state=active]:text-purple-400">
                            <Gift className="h-4 w-4" />
                            Program Referral
                        </TabsTrigger>
                    </TabsList>

                    <div className="flex items-center gap-2">
                        <div className="relative">
                            <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-500" />
                            <Input 
                                placeholder="Cari..." 
                                className="pl-9 bg-zinc-900/50 border-zinc-800 rounded-lg h-9 w-64 focus:ring-1 focus:ring-blue-500/50"
                            />
                        </div>
                        <Button variant="outline" size="sm" className="border-zinc-800 bg-zinc-900/50 rounded-lg text-zinc-400">
                            <Filter className="h-4 w-4" />
                        </Button>
                    </div>
                </div>

                <TabsContent value="promotions" className="mt-0 space-y-4">
                    <PromotionList />
                </TabsContent>

                <TabsContent value="referrals" className="mt-0">
                    <ReferralList />
                </TabsContent>
            </Tabs>
        </div>
    );
}
