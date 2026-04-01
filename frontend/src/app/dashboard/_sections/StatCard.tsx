'use client';

import React from 'react';
import { LucideIcon, TrendingUp, TrendingDown } from 'lucide-react';
import { cn } from '@/lib/utils';

interface StatCardProps {
    title: string;
    value: string | number;
    sub?: string;
    icon: LucideIcon;
    color: 'emerald' | 'sky' | 'purple' | 'rose' | 'amber' | 'orange' | 'indigo' | 'red' | 'zinc_neutral';
    up?: boolean;
}

const colorMap = {
    emerald: {
        icon: 'text-emerald-400 bg-emerald-400/10',
        glow: 'shadow-[0_0_20px_rgba(16,185,129,0.08)]',
        value: 'text-emerald-400',
        border: 'border-emerald-500/10',
    },
    sky: {
        icon: 'text-sky-400 bg-sky-400/10',
        glow: 'shadow-[0_0_20px_rgba(14,165,233,0.08)]',
        value: 'text-sky-400',
        border: 'border-sky-500/10',
    },
    purple: {
        icon: 'text-purple-400 bg-purple-400/10',
        glow: 'shadow-[0_0_20px_rgba(139,92,246,0.08)]',
        value: 'text-purple-400',
        border: 'border-purple-500/10',
    },
    rose: {
        icon: 'text-rose-400 bg-rose-400/10',
        glow: 'shadow-[0_0_20px_rgba(244,63,94,0.08)]',
        value: 'text-rose-400',
        border: 'border-rose-500/10',
    },
    amber: {
        icon: 'text-amber-400 bg-amber-400/10',
        glow: 'shadow-[0_0_20px_rgba(245,158,11,0.08)]',
        value: 'text-amber-400',
        border: 'border-amber-500/10',
    },
    orange: {
        icon: 'text-orange-400 bg-orange-400/10',
        glow: 'shadow-[0_0_20px_rgba(249,115,22,0.08)]',
        value: 'text-orange-400',
        border: 'border-orange-500/10',
    },
    indigo: {
        icon: 'text-indigo-400 bg-indigo-400/10',
        glow: 'shadow-[0_0_20px_rgba(99,102,241,0.08)]',
        value: 'text-indigo-400',
        border: 'border-indigo-500/10',
    },
    red: {
        icon: 'text-red-400 bg-red-400/10',
        glow: 'shadow-[0_0_20px_rgba(239,68,68,0.08)]',
        value: 'text-red-400',
        border: 'border-red-500/10',
    },
    zinc_neutral: {
        icon: 'text-zinc-400 bg-zinc-400/10',
        glow: '',
        value: 'text-zinc-300',
        border: 'border-zinc-800',
    },
};

export function StatCard({ title, value, sub, icon: Icon, color, up }: StatCardProps) {
    const c = colorMap[color];

    return (
        <div className={cn(
            'relative group rounded-2xl p-5 bg-white/[0.025] border backdrop-blur-sm',
            'hover:bg-white/[0.035] transition-all duration-300 overflow-hidden',
            c.glow, c.border
        )}>
            {/* Background accent */}
            <div className={cn('absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-2xl', c.glow)} />

            <div className="relative flex items-start justify-between gap-3">
                <div className="flex-1 min-w-0">
                    <p className="text-[11px] font-semibold uppercase tracking-widest text-zinc-500 mb-2 truncate">{title}</p>
                    <p className={cn('text-2xl font-bold leading-none tracking-tight truncate', c.value)}>{value}</p>
                    {sub && <p className="text-[11px] text-zinc-600 mt-1.5 truncate">{sub}</p>}
                </div>
                <div className={cn('h-9 w-9 rounded-xl flex items-center justify-center shrink-0', c.icon)}>
                    <Icon className="h-4 w-4" />
                </div>
            </div>

            {/* Trend indicator */}
            {up !== undefined && (
                <div className={cn(
                    'absolute bottom-3 right-4 flex items-center gap-1',
                    up ? 'text-emerald-500' : 'text-rose-500'
                )}>
                    {up ? <TrendingUp className="h-3 w-3" /> : <TrendingDown className="h-3 w-3" />}
                </div>
            )}
        </div>
    );
}
