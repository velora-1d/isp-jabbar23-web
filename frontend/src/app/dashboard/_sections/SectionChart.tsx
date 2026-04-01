'use client';

import React from 'react';
import { cn } from '@/lib/utils';

interface SectionChartProps {
    title: string;
    children: React.ReactNode;
    className?: string;
    color?: 'emerald' | 'sky' | 'purple' | 'rose' | 'amber' | 'orange' | 'indigo';
}

const colorBorderMap = {
    emerald: 'border-emerald-500/10',
    sky: 'border-sky-500/10',
    purple: 'border-purple-500/10',
    rose: 'border-rose-500/10',
    amber: 'border-amber-500/10',
    orange: 'border-orange-500/10',
    indigo: 'border-indigo-500/10',
};

const colorDotMap = {
    emerald: 'bg-emerald-400',
    sky: 'bg-sky-400',
    purple: 'bg-purple-400',
    rose: 'bg-rose-400',
    amber: 'bg-amber-400',
    orange: 'bg-orange-400',
    indigo: 'bg-indigo-400',
};

export function SectionChart({ title, children, className, color = 'sky' }: SectionChartProps) {
    return (
        <div className={cn(
            'rounded-2xl p-5 bg-white/[0.025] border backdrop-blur-sm',
            'hover:bg-white/[0.03] transition-all duration-300',
            colorBorderMap[color],
            className
        )}>
            <div className="flex items-center gap-2 mb-4">
                <div className={cn('h-1.5 w-1.5 rounded-full', colorDotMap[color])} />
                <h4 className="text-xs font-bold uppercase tracking-widest text-zinc-400">{title}</h4>
            </div>
            {children}
        </div>
    );
}
