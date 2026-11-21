'use client';

import { useMarketing } from '@/hooks/use-marketing';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { ChevronRight, Trash2, Edit } from 'lucide-react';

export function PromotionList() {
    const { promotions, isLoadingPromotions, deletePromotion } = useMarketing();

    if (isLoadingPromotions) {
        return <div className="grid grid-cols-1 md:grid-cols-3 gap-6 animate-pulse">
            {[1, 2, 3].map(i => (
                <div key={i} className="h-48 bg-zinc-900/50 rounded-xl border border-zinc-800"></div>
            ))}
        </div>;
    }

    if (promotions.length === 0) {
        return (
            <div className="flex flex-col items-center justify-center py-20 text-zinc-500 bg-zinc-900/20 rounded-2xl border border-dashed border-zinc-800">
                <p>Belum ada promosi aktif.</p>
            </div>
        );
    }

    return (
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            {promotions.map((promo: any) => (
                <Card key={promo.id} className="bg-zinc-900/30 border-zinc-800/50 hover:border-blue-500/30 transition-all duration-300 group">
                    <CardHeader className="pb-3">
                        <div className="flex items-center justify-between mb-2">
                            <Badge className={`bg-${promo.type_color}-500/10 text-${promo.type_color}-400 border-${promo.type_color}-500/20`}>
                                {promo.type_label}
                            </Badge>
                            <div className="flex items-center gap-1">
                                <span className={`h-2 w-2 rounded-full bg-${promo.status_color}-500 shrink-0`}></span>
                                <span className={`text-[10px] font-bold uppercase text-${promo.status_color}-400`}>
                                    {promo.status_label}
                                </span>
                            </div>
                        </div>
                        <CardTitle className="text-xl">{promo.name}</CardTitle>
                        <CardDescription className="text-xs font-mono text-blue-400 bg-blue-500/5 px-2 py-1 rounded inline-block">
                            {promo.code}
                        </CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-4">
                        <p className="text-sm text-zinc-500 line-clamp-2">{promo.description || 'Tidak ada deskripsi.'}</p>
                        <div className="grid grid-cols-2 gap-4 pt-2">
                            <div className="space-y-1">
                                <p className="text-[10px] text-zinc-500 uppercase tracking-tighter">Usage</p>
                                <p className="text-sm font-semibold">{promo.usage_count} / {promo.usage_limit || '∞'}</p>
                            </div>
                            <div className="space-y-1 text-right">
                                <p className="text-[10px] text-zinc-500 uppercase tracking-tighter">Expires</p>
                                <p className="text-sm font-semibold font-mono">{promo.end_date}</p>
                            </div>
                        </div>
                        <div className="flex items-center gap-2 pt-2">
                            <Button variant="ghost" size="sm" className="flex-1 h-8 rounded-lg text-zinc-400 group/btn">
                                Detail
                                <ChevronRight className="h-4 w-4 ml-1 group-hover/btn:translate-x-1 transition-transform" />
                            </Button>
                            <Button 
                                variant="ghost" 
                                size="icon" 
                                className="h-8 w-8 text-zinc-500 hover:text-red-400 hover:bg-red-400/10"
                                onClick={() => {
                                    if(confirm('Hapus promosi ini?')) deletePromotion.mutate(promo.id);
                                }}
                            >
                                <Trash2 className="h-4 w-4" />
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            ))}
        </div>
    );
}
