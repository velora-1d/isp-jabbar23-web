import { useQuery } from '@tanstack/react-query';
import axios from '@/lib/axios';

export interface AnalyticsResponse {
    status: string;
    data: {
        network: {
            total_customers: number;
            total_online: number;
            total_offline: number;
            active_pppoe: number;
            hotspot_active: number;
            routers_online?: number;
            total_routers?: number;
            new_customers_month?: number;
            routers: {
                name: string;
                online?: number;
                is_up: boolean;
            }[];
        };
        finance: {
            monthly_revenue: {
                month: number;
                month_name: string;
                total: string | number;
            }[];
            month_revenue: number;
            total_ytd: number;
            collection_rate: number;
            unpaid_receivables: number;
            monthly_invoice_stats?: {
                month: number;
                month_name: string;
                paid: number;
                unpaid: number;
            }[];
        };
        staff: {
            staff_online: number;
            total_staff: number;
            attendance_rate: number;
        };
        invoices: {
            unpaid_count: number;
            overdue_count: number;
        };
        tickets: {
            open_count: number;
            in_progress_count: number;
            closed_this_month?: number;
            sla_breached_count?: number;
            monthly_trend?: { month: number; month_name: string; total: number }[];
        };
        work_orders: {
            pending_count: number;
            completed_this_month: number;
            in_progress_count?: number;
        };
        inventory: {
            low_stock_count: number;
            total_items?: number;
            total_value?: number;
            critical_count?: number;
            pending_po_count?: number;
            total_categories?: number;
            items_in_month?: number;
            items_out_month?: number;
            total_vendors?: number;
            low_stock_items?: { name: string; stock: number; min_stock: number }[];
            by_category?: { category: string; count: number }[];
            mutasi_stok_bulanan?: { name: string; masuk: number; keluar: number }[];
        };
        customer_growth: {
            month: number;
            month_name: string;
            year: number;
            total: number;
        }[];
        payment_dist: {
            cash: number;
            manual_transfer: number;
            payment_gateway: number;
        };
    };
}

export function useAnalytics() {
    return useQuery<AnalyticsResponse>({
        queryKey: ['analytics'],
        queryFn: async () => {
            const response = await axios.get('/api/admin/analytics');
            return response.data;
        },
        refetchInterval: 60000,
        refetchOnWindowFocus: false,
        staleTime: 30000,
        retry: 1,
    });
}
