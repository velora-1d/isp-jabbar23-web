import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import axios from '@/lib/axios';
import { Customer, CustomersResponse, CustomerFormData } from '@/types/customer';

export function useCustomers(filters: Record<string, any> = {}) {
    return useQuery<CustomersResponse>({
        queryKey: ['customers', filters],
        queryFn: async () => {
            const response = await axios.get('/api/admin/customers', { params: filters });
            return response.data;
        },
    });
}

export function useCustomer(id: string) {
    return useQuery<Customer>({
        queryKey: ['customer', id],
        queryFn: async () => {
            const response = await axios.get(`/api/admin/customers/${id}`);
            return response.data;
        },
        enabled: !!id,
    });
}

export function useUpdateCustomerStatus() {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: async ({ id, status, notes }: { id: string; status: string; notes?: string }) => {
            const response = await axios.patch(`/api/admin/customers/${id}/status`, { status, notes });
            return response.data;
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['customers'] });
            queryClient.invalidateQueries({ queryKey: ['customer'] });
        },
    });
}

export function useCustomerFormData() {
    return useQuery<CustomerFormData>({
        queryKey: ['customer-form-data'],
        queryFn: async () => {
            const response = await axios.get('/api/admin/customers/form-data');
            return response.data;
        },
    });
}

export function useCreateCustomer() {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: async (customerData: any) => {
            const response = await axios.post('/api/admin/customers', customerData);
            return response.data;
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['customers'] });
        },
    });
}

export function useUpdateCustomer() {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: async ({ id, data: customerData }: { id: string; data: any }) => {
            const response = await axios.put(`/api/admin/customers/${id}`, customerData);
            return response.data;
        },
        onSuccess: (_, variables) => {
            queryClient.invalidateQueries({ queryKey: ['customers'] });
            queryClient.invalidateQueries({ queryKey: ['customer', variables.id] });
        },
    });
}

export function useSyncCustomerToMikrotik() {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: async (id: string) => {
            const response = await axios.post(`/api/admin/customers/${id}/sync-mikrotik`);
            return response.data;
        },
        onSuccess: (_, id) => {
            queryClient.invalidateQueries({ queryKey: ['customer', id] });
        },
    });
}
