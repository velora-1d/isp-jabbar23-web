'use client';

import { useAuth } from '@/hooks/use-auth';
import { useRouter, usePathname } from 'next/navigation';
import { useEffect } from 'react';
import Link from 'next/link';
import { 
    LayoutDashboard, 
    Users, 
    Ticket, 
    Settings, 
    LogOut, 
    Menu, 
    X,
    Bell,
    Search,
    CreditCard,
    Activity,
    Package,
    Network,
    Warehouse,
    ClipboardList,
    Megaphone
} from 'lucide-react';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';
import { useState } from 'react';

export default function DashboardLayout({
    children,
}: {
    children: React.ReactNode;
}) {
    const { user, isLoading, logout } = useAuth();
    const router = useRouter();
    const pathname = usePathname();
    const [isSidebarOpen, setIsSidebarOpen] = useState(false);

    useEffect(() => {
        if (!isLoading && !user) {
            router.push('/login');
        }
    }, [user, isLoading, router]);

    if (isLoading) {
        return (
            <div className="flex min-h-screen items-center justify-center bg-zinc-950 text-zinc-100">
                <div className="flex flex-col items-center gap-4">
                    <div className="h-12 w-12 animate-spin rounded-full border-4 border-blue-500 border-t-transparent"></div>
                    <p className="animate-pulse font-medium">Menyiapkan Workspace...</p>
                </div>
            </div>
        );
    }

    if (!user) return null;

    const navItems = [
        { name: 'Dashboard', href: '/dashboard', icon: LayoutDashboard },
        { name: 'Analytics & Reports', href: '/dashboard/admin/analytics', icon: Activity, admin: true },
        { name: 'Pelanggan', href: '/dashboard/admin/customers', icon: Users, admin: true },
        { name: 'Paket Internet', href: '/dashboard/admin/packages', icon: Package, admin: true },
        { name: 'Billing & Invoice', href: '/dashboard/admin/billing', icon: CreditCard, admin: true },
        { name: 'Finansial & Pengeluaran', href: '/dashboard/admin/finance', icon: CreditCard, admin: true },
        { name: 'Absensi & HRD', href: '/dashboard/admin/attendance', icon: ClipboardList, admin: true },
        { name: 'Network Monitoring', href: '/dashboard/admin/network', icon: Activity, admin: true },
        { name: 'Infrastruktur Jaringan', href: '/dashboard/admin/infrastructure', icon: Network, admin: true },
        { name: 'Gudang & Inventaris', href: '/dashboard/admin/inventory', icon: Warehouse, admin: true },
        { name: 'Work Orders (SPK)', href: '/dashboard/admin/work-orders', icon: ClipboardList, admin: true },
        { name: 'CRM & Leads', href: '/dashboard/admin/leads', icon: Users, admin: true },
        { name: 'Marketing & Promo', href: '/dashboard/admin/marketing', icon: Megaphone, admin: true },
        { name: 'Manajemen Tiket', href: '/dashboard/admin/tickets', icon: Ticket, admin: true },
        { name: 'Settings', href: '/dashboard/settings', icon: Settings },
    ];

    const filteredNavItems = navItems.filter(item => !item.admin || user.role === 'admin' || user.role === 'super_admin');

    return (
        <div className="flex min-h-screen bg-zinc-950 text-zinc-100 selection:bg-blue-500/30">
            {/* Sidebar Desktop */}
            <aside className="hidden md:flex w-72 flex-col fixed inset-y-0 z-50 border-r border-zinc-800/50 bg-zinc-950/80 backdrop-blur-xl">
                <div className="flex h-16 items-center px-6 border-b border-zinc-800/50">
                    <div className="flex items-center gap-2">
                        <div className="h-8 w-8 rounded-lg bg-gradient-to-br from-blue-600 to-cyan-500 flex items-center justify-center shadow-lg shadow-blue-500/20">
                            <span className="font-bold text-white italic">V</span>
                        </div>
                        <span className="font-bold text-xl tracking-tight bg-gradient-to-r from-white to-zinc-400 bg-clip-text text-transparent">
                            Jabbar23
                        </span>
                    </div>
                </div>

                <div className="flex-1 overflow-y-auto p-4 space-y-2">
                    {filteredNavItems.map((item) => (
                        <Link
                            key={item.href}
                            href={item.href}
                            className={cn(
                                "flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group",
                                pathname === item.href 
                                    ? "bg-blue-600/10 text-blue-400 border border-blue-500/20" 
                                    : "text-zinc-400 hover:text-zinc-100 hover:bg-zinc-900"
                            )}
                        >
                            <item.icon className={cn(
                                "h-5 w-5 transition-colors",
                                pathname === item.href ? "text-blue-400" : "group-hover:text-zinc-100"
                            )} />
                            <span className="font-medium text-sm">{item.name}</span>
                        </Link>
                    ))}
                </div>

                <div className="p-4 border-t border-zinc-800/50">
                    <div className="flex items-center gap-3 px-4 py-3">
                        <div className="h-10 w-10 rounded-full bg-zinc-800 border border-zinc-700 flex items-center justify-center text-zinc-400 font-bold">
                            {user.name.substring(0, 2).toUpperCase()}
                        </div>
                        <div className="flex-1 min-w-0">
                            <p className="text-sm font-semibold truncate">{user.name}</p>
                            <p className="text-xs text-zinc-500 truncate capitalize">{user.role}</p>
                        </div>
                    </div>
                    <Button 
                        variant="ghost" 
                        onClick={() => logout()}
                        className="w-full justify-start gap-3 mt-2 text-zinc-400 hover:text-red-400 hover:bg-red-400/10 rounded-xl"
                    >
                        <LogOut className="h-5 w-5" />
                        <span className="text-sm font-medium">Keluar</span>
                    </Button>
                </div>
            </aside>

            {/* Main Content */}
            <main className="flex-1 md:pl-72 transition-all duration-300">
                {/* Header Navbar */}
                <header className="sticky top-0 z-40 h-16 border-b border-zinc-800/50 bg-zinc-950/80 backdrop-blur-xl flex items-center justify-between px-6">
                    <div className="flex items-center gap-4">
                        <Button 
                            variant="ghost" 
                            size="icon" 
                            className="md:hidden text-zinc-400"
                            onClick={() => setIsSidebarOpen(true)}
                        >
                            <Menu className="h-6 w-6" />
                        </Button>
                        <div className="relative hidden sm:block">
                            <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-500" />
                            <input 
                                type="text" 
                                placeholder="Cari data..." 
                                className="pl-10 pr-4 py-1.5 bg-zinc-900/50 border border-zinc-800 rounded-lg text-sm w-64 focus:ring-2 focus:ring-blue-500/50 transition-all outline-none"
                            />
                        </div>
                    </div>

                    <div className="flex items-center gap-4">
                        <Button variant="ghost" size="icon" className="text-zinc-400 hover:text-zinc-100 relative">
                            <Bell className="h-5 w-5" />
                            <span className="absolute top-2 right-2 h-2 w-2 rounded-full bg-blue-500 ring-2 ring-zinc-950"></span>
                        </Button>
                    </div>
                </header>

                {/* Page Content */}
                <div className="p-6 md:p-10 animate-in fade-in slide-in-from-bottom-4 duration-700">
                    {children}
                </div>
            </main>

            {/* Mobile Sidebar Overlay */}
            {isSidebarOpen && (
                <div className="fixed inset-0 z-[60] bg-black/60 backdrop-blur-sm md:hidden" onClick={() => setIsSidebarOpen(false)}>
                    <div className="w-72 h-full bg-zinc-950 p-6 flex flex-col animate-in slide-in-from-left duration-300" onClick={e => e.stopPropagation()}>
                        <div className="flex items-center justify-between mb-8">
                            <div className="flex items-center gap-2">
                                <span className="font-bold text-xl uppercase italic tracking-widest text-blue-500">Jabbar23</span>
                            </div>
                            <Button variant="ghost" size="icon" onClick={() => setIsSidebarOpen(false)}>
                                <X className="h-6 w-6" />
                            </Button>
                        </div>
                        <div className="flex-1 space-y-2">
                            {filteredNavItems.map((item) => (
                                <Link
                                    key={item.href}
                                    href={item.href}
                                    onClick={() => setIsSidebarOpen(false)}
                                    className={cn(
                                        "flex items-center gap-3 px-4 py-3 rounded-xl transition-all",
                                        pathname === item.href 
                                            ? "bg-blue-600 shadow-lg shadow-blue-500/20 text-white" 
                                            : "text-zinc-400 hover:text-zinc-100 hover:bg-zinc-900"
                                    )}
                                >
                                    <item.icon className="h-5 w-5" />
                                    <span className="font-medium text-sm">{item.name}</span>
                                </Link>
                            ))}
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}
