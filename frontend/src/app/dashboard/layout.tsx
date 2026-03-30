'use client';

import { useAuth } from '@/hooks/use-auth';
import { useRouter, usePathname } from 'next/navigation';
import { useEffect, useState } from 'react';
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
    Megaphone,
    BookOpen,
    ShieldCheck,
    Wallet,
    Rss,
    Wrench,
    Map,
    UserCheck,
    History,
    FileText,
    FileDiff,
    Truck,
    ShoppingCart,
    Building2,
    Calendar,
    Briefcase,
    ShieldAlert,
    Database,
    Zap
} from 'lucide-react';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';

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
            <div className="flex min-h-screen items-center justify-center bg-zinc-950 text-emerald-500">
                <div className="flex flex-col items-center gap-4">
                    <div className="h-12 w-12 animate-spin rounded-full border-4 border-emerald-500 border-t-transparent"></div>
                    <p className="animate-pulse font-medium">Menyiapkan Workspace Jabbar23...</p>
                </div>
            </div>
        );
    }

    if (!user) return null;

    // Pengelompokan Menu Navigasi (Master Plan 42 Halaman)
    const navGroups = [
        {
            title: "MAIN",
            items: [
                { name: 'Dashboard Admin', href: '/dashboard', icon: LayoutDashboard },
                { name: 'Analytics & Reports', href: '/dashboard/admin/analytics', icon: Activity },
            ]
        },
        {
            title: "CUSTOMER & SALES",
            items: [
                { name: 'List Pelanggan', href: '/dashboard/admin/customers', icon: Users },
                { name: 'Registrasi (Form)', href: '/dashboard/admin/customers/create', icon: UserCheck },
                { name: 'Leads CRM', href: '/dashboard/admin/leads', icon: Zap },
                { name: 'Mitra & Program Referral', href: '/dashboard/admin/referrals', icon: Building2 },
            ]
        },
        {
            title: "BILLING & FINANCE",
            items: [
                { name: 'Billing & Invoice', href: '/dashboard/admin/billing', icon: CreditCard },
                { name: 'Payment History', href: '/dashboard/admin/finance/payments', icon: History },
                { name: 'Expense Tracking', href: '/dashboard/admin/finance/expenses', icon: Wallet },
                { name: 'Credit Note / Adjustment', href: '/dashboard/coming-soon?src=cn', icon: FileDiff },
                { name: 'Internet Packages', href: '/dashboard/admin/packages', icon: Package },
            ]
        },
        {
            title: "NETWORK & INFRA",
            items: [
                { name: 'Network Monitoring (OLT)', href: '/dashboard/admin/network', icon: Rss },
                { name: 'Infrastruktur / ODP', href: '/dashboard/admin/infrastructure', icon: Network },
                { name: 'Router (Mikrotik)', href: '/dashboard/coming-soon?src=mkt', icon: Activity },
                { name: 'Hotspot Voucher', href: '/dashboard/coming-soon?src=vch', icon: Zap },
            ]
        },
        {
            title: "INVENTORY & OPERATION",
            items: [
                { name: 'Gudang & Stok Barang', href: '/dashboard/admin/inventory', icon: Warehouse },
                { name: 'Purchase Order (PO)', href: '/dashboard/coming-soon?src=po', icon: ShoppingCart },
                { name: 'Work Orders (SPK)', href: '/dashboard/admin/work-orders', icon: ClipboardList },
                { name: 'Vendor / Supplier', href: '/dashboard/coming-soon?src=ven', icon: Truck },
            ]
        },
        {
            title: "MARKETING & SUPPORT",
            items: [
                { name: 'Marketing Blast (WA)', href: '/dashboard/admin/marketing', icon: Megaphone },
                { name: 'Promo & Diskon', href: '/dashboard/coming-soon?src=prm', icon: Zap },
                { name: 'Tiket Support', href: '/dashboard/admin/tickets', icon: Ticket },
                { name: 'Knowledge Base', href: '/dashboard/admin/support/knowledge-base', icon: BookOpen },
                { name: 'SLA Management', href: '/dashboard/admin/support/sla', icon: ShieldCheck },
            ]
        },
        {
            title: "HRD & EMPLOYEE",
            items: [
                { name: 'Karyawan / User', href: '/dashboard/coming-soon?src=usr', icon: Users },
                { name: 'Absensi (GPS Clock)', href: '/dashboard/admin/attendance', icon: Map },
                { name: 'Payroll / Gaji', href: '/dashboard/coming-soon?src=py', icon: BanknoteIcon },
                { name: 'Cuti / Izin Approval', href: '/dashboard/coming-soon?src=leave', icon: Calendar },
            ]
        },
        {
            title: "TRACKING",
            items: [
                { name: 'Technician Map', href: '/dashboard/coming-soon?src=tmap', icon: Map },
                { name: 'Installation Reports', href: '/dashboard/coming-soon?src=inst', icon: FileText },
                { name: 'Scheduling', href: '/dashboard/coming-soon?src=sch', icon: Calendar },
            ]
        },
        {
            title: "SYSTEM",
            items: [
                { name: 'Settings', href: '/dashboard/settings', icon: Settings },
                { name: 'Roles & Permission', href: '/dashboard/coming-soon?src=role', icon: ShieldAlert },
                { name: 'Audit / Activity Log', href: '/dashboard/coming-soon?src=log', icon: FileText },
                { name: 'Database / Backup', href: '/dashboard/coming-soon?src=db', icon: Database },
            ]
        }
    ];

    return (
        <div className="flex h-screen overflow-hidden bg-[#060D12] text-zinc-100 selection:bg-emerald-500/30 font-body">
            {/* Sidebar Desktop */}
            <aside className="hidden md:flex w-72 flex-col fixed inset-y-0 z-50 border-r border-zinc-800/50 bg-[#060D12]/80 backdrop-blur-xl">
                <div className="flex h-16 items-center px-6 border-b border-white/5">
                    <div className="flex items-center gap-2">
                        <div className="h-8 w-8 rounded-lg bg-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                            <span className="font-bold text-white italic">V</span>
                        </div>
                        <span className="font-bold text-xl tracking-tight bg-gradient-to-r from-white to-zinc-400 bg-clip-text text-transparent font-heading">
                            Jabbar23
                        </span>
                    </div>
                </div>

                <div className="flex-1 overflow-y-auto p-4 space-y-6 [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">
                    {navGroups.map((group) => (
                        <div key={group.title} className="space-y-1">
                            <h3 className="px-4 text-[10px] font-bold tracking-widest text-zinc-500 mb-2 uppercase">
                                {group.title}
                            </h3>
                            {group.items.map((item) => (
                                <Link
                                    key={item.href}
                                    href={item.href}
                                    className={cn(
                                        "flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200 group relative",
                                        pathname === item.href 
                                            ? "bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 shadow-[0_0_15px_rgba(34,197,94,0.1)]" 
                                            : "text-zinc-500 hover:text-zinc-100 hover:bg-white/[0.03]"
                                    )}
                                >
                                    <item.icon className={cn(
                                        "h-4 w-4 transition-colors",
                                        pathname === item.href ? "text-emerald-400" : "group-hover:text-zinc-100"
                                    )} />
                                    <span className="font-medium text-xs truncate">{item.name}</span>
                                    {pathname === item.href && (
                                        <div className="absolute right-2 h-1 w-1 rounded-full bg-emerald-500" />
                                    )}
                                </Link>
                            ))}
                        </div>
                    ))}
                </div>

                <div className="p-4 border-t border-white/5 bg-white/[0.02]">
                    <div className="flex items-center gap-3 px-4 py-2">
                        <div className="h-8 w-8 rounded-lg bg-zinc-800 border border-zinc-700 flex items-center justify-center text-emerald-500 font-bold text-xs">
                            {user.name?.substring(0, 2).toUpperCase()}
                        </div>
                        <div className="flex-1 min-w-0">
                            <p className="text-xs font-semibold truncate">{user.name}</p>
                            <p className="text-[10px] text-zinc-500 truncate capitalize">{user.role}</p>
                        </div>
                    </div>
                    <Button 
                        variant="ghost" 
                        size="sm"
                        onClick={() => logout()}
                        className="w-full justify-start gap-3 mt-2 text-zinc-500 hover:text-red-400 hover:bg-red-400/10 rounded-lg h-9"
                    >
                        <LogOut className="h-4 w-4" />
                        <span className="text-xs font-medium">Keluar</span>
                    </Button>
                </div>
            </aside>

            {/* Main Content */}
            <main className="flex-1 md:pl-72 h-screen overflow-y-auto transition-all duration-300">
                {/* Header Navbar */}
                <header className="sticky top-0 z-40 h-16 border-b border-white/5 bg-[#060D12]/80 backdrop-blur-xl flex items-center justify-between px-6">
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
                                className="pl-10 pr-4 py-1.5 bg-white/[0.03] border border-white/5 rounded-lg text-xs w-64 focus:ring-1 focus:ring-emerald-500/50 transition-all outline-none"
                            />
                        </div>
                    </div>

                    <div className="flex items-center gap-4">
                        <Button variant="ghost" size="icon" className="text-zinc-400 hover:text-emerald-400 relative">
                            <Bell className="h-5 w-5" />
                            <span className="absolute top-2 right-2 h-2 w-2 rounded-full bg-emerald-500 ring-2 ring-[#060D12]"></span>
                        </Button>
                    </div>
                </header>

                {/* Page Content */}
                <div className="p-6 md:p-8 animate-in fade-in slide-in-from-bottom-2 duration-700">
                    {children}
                </div>
            </main>

            {/* Mobile Sidebar Overlay */}
            {isSidebarOpen && (
                <div className="fixed inset-0 z-[60] bg-black/80 backdrop-blur-sm md:hidden" onClick={() => setIsSidebarOpen(false)}>
                    <div className="w-72 h-full bg-[#060D12] p-6 flex flex-col animate-in slide-in-from-left duration-300" onClick={e => e.stopPropagation()}>
                        <div className="flex items-center justify-between mb-8">
                            <div className="flex items-center gap-2">
                                <div className="h-8 w-8 rounded-lg bg-emerald-600 flex items-center justify-center">
                                    <span className="font-bold text-white italic">V</span>
                                </div>
                                <span className="font-bold text-xl tracking-tight text-white font-heading">Jabbar23</span>
                            </div>
                            <Button variant="ghost" size="icon" onClick={() => setIsSidebarOpen(false)}>
                                <X className="h-6 w-6 text-zinc-400" />
                            </Button>
                        </div>
                        <div className="flex-1 overflow-y-auto space-y-6">
                           {navGroups.map((group) => (
                                <div key={group.title} className="space-y-1">
                                    <h3 className="px-4 text-[10px] font-bold tracking-widest text-zinc-600 mb-2 uppercase">
                                        {group.title}
                                    </h3>
                                    {group.items.map((item) => (
                                        <Link
                                            key={item.href}
                                            href={item.href}
                                            onClick={() => setIsSidebarOpen(false)}
                                            className={cn(
                                                "flex items-center gap-3 px-4 py-2 rounded-lg transition-all",
                                                pathname === item.href 
                                                    ? "bg-emerald-600 shadow-lg shadow-emerald-500/20 text-white" 
                                                    : "text-zinc-500 hover:text-zinc-100"
                                            )}
                                        >
                                            <item.icon className="h-4 w-4" />
                                            <span className="font-medium text-xs">{item.name}</span>
                                        </Link>
                                    ))}
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}

// Tambahkan helper icon
function BanknoteIcon(props: any) {
  return (
    <svg
      {...props}
      xmlns="http://www.w3.org/2000/svg"
      width="24"
      height="24"
      viewBox="0 0 24 24"
      fill="none"
      stroke="currentColor"
      strokeWidth="2"
      strokeLinecap="round"
      strokeLinejoin="round"
    >
      <rect width="20" height="12" x="2" y="6" rx="2" />
      <circle cx="12" cy="12" r="2" />
      <path d="M6 12h.01M18 12h.01" />
    </svg>
  )
}

