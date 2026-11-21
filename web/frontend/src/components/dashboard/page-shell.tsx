'use client';

import React from 'react';
import { usePathname } from 'next/navigation';
import Link from 'next/link';
import { ChevronRight, Home } from 'lucide-react';
import { cn } from '@/lib/utils';

interface DashboardPageShellProps {
  title: string;
  description?: string;
  children: React.ReactNode;
  icon?: React.ElementType;
  actions?: React.ReactNode;
  className?: string;
}

export function DashboardPageShell({
  title,
  description,
  children,
  icon: Icon,
  actions,
  className,
}: DashboardPageShellProps) {
  const pathname = usePathname();
  
  // Generate breadcrumbs from pathname
  const pathSegments = pathname.split('/').filter(segment => segment !== '');
  const breadcrumbs = pathSegments.map((segment, index) => {
    const href = `/${pathSegments.slice(0, index + 1).join('/')}`;
    const label = segment.charAt(0).toUpperCase() + segment.slice(1).replace(/-/g, ' ');
    const isLast = index === pathSegments.length - 1;
    
    return { label, href, isLast };
  });

  return (
    <div className={cn("space-y-6 animate-in fade-in slide-in-from-bottom-4 duration-700", className)}>
      {/* Breadcrumbs */}
      <nav className="flex items-center gap-2 text-sm text-muted-foreground mb-2">
        <Link 
          href="/dashboard" 
          className="hover:text-primary transition-colors flex items-center gap-1"
        >
          <Home className="h-3.5 w-3.5" />
          <span className="hidden sm:inline">Dashboard</span>
        </Link>
        
        {breadcrumbs.slice(1).map((crumb, i) => (
          <React.Fragment key={crumb.href}>
            <ChevronRight className="h-3.5 w-3.5 text-zinc-600" />
            {crumb.isLast ? (
              <span className="text-primary font-medium">{crumb.label}</span>
            ) : (
              <Link 
                href={crumb.href} 
                className="hover:text-primary transition-colors"
              >
                {crumb.label}
              </Link>
            )}
          </React.Fragment>
        ))}
      </nav>

      <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <div className="flex items-center gap-3 mb-1">
            {Icon && (
              <div className="p-2 rounded-xl bg-primary/10 border border-primary/20">
                <Icon className="h-6 w-6 text-primary" />
              </div>
            )}
            <h1 className="text-3xl font-heading font-bold bg-gradient-to-r from-white to-zinc-400 bg-clip-text text-transparent">
              {title}
            </h1>
          </div>
          {description && (
            <p className="text-muted-foreground text-sm max-w-2xl px-1">
              {description}
            </p>
          )}
        </div>
        
        {actions && (
          <div className="flex items-center gap-2">
            {actions}
          </div>
        )}
      </div>

      {/* Main Content Area with Glassmorphism */}
      <div className="min-h-[500px] w-full rounded-2xl border border-white/5 bg-zinc-900/40 backdrop-blur-sm p-6 relative overflow-hidden group">
        {/* Subtle Background Glow */}
        <div className="absolute -top-24 -right-24 w-64 h-64 bg-primary/5 rounded-full blur-3xl group-hover:bg-primary/10 transition-colors duration-1000" />
        
        <div className="relative z-10">
          {children}
        </div>
        
        {/* Futuristic Corner Accents */}
        <div className="absolute top-0 left-0 w-8 h-8 border-t-2 border-l-2 border-primary/20 rounded-tl-xl" />
        <div className="absolute bottom-0 right-0 w-8 h-8 border-b-2 border-r-2 border-primary/20 rounded-br-xl" />
      </div>
    </div>
  );
}
