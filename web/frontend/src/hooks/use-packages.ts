import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import axios from '@/lib/axios';
import { Package, CreatePackageRequest, UpdatePackageRequest } from '@/types/package';
import { toast } from 'sonner';

export const usePackages = (onlyActive = false) => {
  const queryClient = useQueryClient();

  const { data: packages = [], isLoading, error } = useQuery<Package[]>({
    queryKey: ['packages', { onlyActive }],
    queryFn: async () => {
      const response = await axios.get('/admin/packages', {
        params: { only_active: onlyActive },
      });
      return response.data;
    },
  });

  const createPackage = useMutation({
    mutationFn: async (data: CreatePackageRequest) => {
      const response = await axios.post('/admin/packages', data);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['packages'] });
      toast.success('Paket berhasil dibuat');
    },
    onError: (error: any) => {
      toast.error(error.response?.data?.message || 'Gagal membuat paket');
    },
  });

  const updatePackage = useMutation({
    mutationFn: async ({ id, data }: { id: number; data: UpdatePackageRequest }) => {
      const response = await axios.put(`/admin/packages/${id}`, data);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['packages'] });
      toast.success('Paket berhasil diperbarui');
    },
    onError: (error: any) => {
      toast.error(error.response?.data?.message || 'Gagal memperbarui paket');
    },
  });

  const deletePackage = useMutation({
    mutationFn: async (id: number) => {
      const response = await axios.delete(`/admin/packages/${id}`);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['packages'] });
      toast.success('Paket berhasil dihapus');
    },
    onError: (error: any) => {
      toast.error(error.response?.data?.message || 'Gagal menghapus paket');
    },
  });

  return {
    packages,
    isLoading,
    error,
    createPackage,
    updatePackage,
    deletePackage,
  };
};
