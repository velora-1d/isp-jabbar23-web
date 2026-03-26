import { useQuery } from '@tanstack/react-query';
import axios from '@/lib/axios';

export const useAnalytics = () => {
    return useQuery({
        queryKey: ['admin-analytics'],
        queryFn: async () => {
            const { data } = await axios.get('/api/admin/analytics');
            return data.data;
        },
        refetchInterval: 60000, // Refresh every minute for real-time feel
    });
};
