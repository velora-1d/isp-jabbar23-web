import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/lib/axios';
import { WorkOrder } from '@/types/work-order';

export function useWorkOrders() {
    const queryClient = useQueryClient();

    const workOrders = useQuery({
        queryKey: ['admin', 'work-orders'],
        queryFn: async () => {
            const { data } = await api.get('/admin/work-orders');
            return data;
        }
    });

    const createWorkOrder = useMutation({
        mutationFn: async (data: Partial<WorkOrder>) => {
            const { data: res } = await api.post('/admin/work-orders', data);
            return res;
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin', 'work-orders'] });
        }
    });

    const updateStatus = useMutation({
        mutationFn: async ({ id, status, notes }: { id: string; status: string; notes?: string }) => {
            const { data } = await api.patch(`/admin/work-orders/${id}/status`, { status, notes });
            return data;
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin', 'work-orders'] });
        }
    });

    return {
        workOrders,
        createWorkOrder,
        updateStatus
    };
}
