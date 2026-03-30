import { useQuery } from '@tanstack/react-query';
import axios from '@/lib/axios';

export interface AnalyticsResponse {
    status: string;
    data: {
        network: {
            total_customers: number;
            total_online: number;
            total_offline: number;
            routers: {
                name: string;
                online: number;
                is_up: boolean;
            }[];
        };
        finance: {
            monthly_revenue: {
                month: number;
                month_name: string;
                total: string | number;
            }[];
            total_ytd: string | number;
            collection_rate: number;
            unpaid_receivables: string | number;
        };
        staff: {
            staff_online: number;
            total_staff: number;
            attendance_rate: number;
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
        refetchInterval: 30000, // Refresh every 30 seconds for "real-time" feel
    });
}
