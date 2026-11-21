import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import axios from '@/lib/axios';
import { Olt, Odp, CreateOltRequest, CreateOdpRequest } from '@/types/infrastructure';
import { toast } from 'sonner';

export const useInfrastructure = () => {
  const queryClient = useQueryClient();

  // OLT Queries & Mutations
  const olts = useQuery<Olt[]>({
    queryKey: ['olts'],
    queryFn: async () => {
      const response = await axios.get('/admin/olts');
      return response.data;
    },
  });

  const createOlt = useMutation({
    mutationFn: async (data: any) => {
      const response = await axios.post('/admin/olts', data);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['olts'] });
      toast.success('OLT berhasil ditambahkan');
    },
    onError: (error: any) => {
      toast.error(error.response?.data?.message || 'Gagal menambahkan OLT');
    },
  });

  const updateOlt = useMutation({
    mutationFn: async ({ id, data }: { id: number; data: any }) => {
      const response = await axios.put(`/admin/olts/${id}`, data);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['olts'] });
      toast.success('OLT berhasil diperbarui');
    },
    onError: (error: any) => {
      toast.error(error.response?.data?.message || 'Gagal memperbarui OLT');
    },
  });

  const deleteOlt = useMutation({
    mutationFn: async (id: number) => {
      const response = await axios.delete(`/admin/olts/${id}`);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['olts'] });
      toast.success('OLT berhasil dihapus');
    },
  });

  // ODP Queries & Mutations
  const odps = useQuery<Odp[]>({
    queryKey: ['odps'],
    queryFn: async () => {
      const response = await axios.get('/admin/odps');
      return response.data;
    },
  });

  const createOdp = useMutation({
    mutationFn: async (data: any) => {
      const response = await axios.post('/admin/odps', data);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['odps'] });
      toast.success('ODP berhasil ditambahkan');
    },
    onError: (error: any) => {
      toast.error(error.response?.data?.message || 'Gagal menambahkan ODP');
    },
  });

  const updateOdp = useMutation({
    mutationFn: async ({ id, data }: { id: number; data: any }) => {
      const response = await axios.put(`/admin/odps/${id}`, data);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['odps'] });
      toast.success('ODP berhasil diperbarui');
    },
    onError: (error: any) => {
      toast.error(error.response?.data?.message || 'Gagal memperbarui ODP');
    },
  });

  const deleteOdp = useMutation({
    mutationFn: async (id: number) => {
      const response = await axios.delete(`/admin/odps/${id}`);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['odps'] });
      toast.success('ODP berhasil dihapus');
    },
  });

  return {
    olts,
    createOlt,
    updateOlt,
    deleteOlt,
    odps,
    createOdp,
    updateOdp,
    deleteOdp,
  };
};
