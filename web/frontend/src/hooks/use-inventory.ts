import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import axios from '@/lib/axios';
import { InventoryItem, InventoryCategory, Location, InventoryTransaction } from '@/types/inventory';
import { toast } from 'sonner';

export const useInventory = () => {
    const queryClient = useQueryClient();

    const items = useQuery<InventoryItem[]>({
        queryKey: ['inventory-items'],
        queryFn: async () => {
            const response = await axios.get('/admin/inventory');
            return response.data;
        },
    });

    const categories = useQuery<InventoryCategory[]>({
        queryKey: ['inventory-categories'],
        queryFn: async () => {
            const response = await axios.get('/admin/inventory/categories');
            return response.data;
        },
    });

    const locations = useQuery<Location[]>({
        queryKey: ['inventory-locations'],
        queryFn: async () => {
            const response = await axios.get('/admin/inventory/locations');
            return response.data;
        },
    });

    const createItem = useMutation({
        mutationFn: async (data: any) => {
            const response = await axios.post('/admin/inventory', data);
            return response.data;
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['inventory-items'] });
            toast.success('Barang berhasil ditambahkan');
        },
        onError: (error: any) => {
            toast.error(error.response?.data?.message || 'Gagal menambahkan barang');
        },
    });

    const recordTransaction = useMutation({
        mutationFn: async (data: any) => {
            const response = await axios.post('/admin/inventory/transactions', data);
            return response.data;
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['inventory-items'] });
            toast.success('Transaksi berhasil dicatat');
        },
        onError: (error: any) => {
            toast.error(error.response?.data?.message || 'Gagal mencatat transaksi');
        },
    });

    return {
        items,
        categories,
        locations,
        createItem,
        recordTransaction,
    };
};
