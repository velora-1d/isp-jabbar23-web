import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import axios from '@/lib/axios';
import { Attendance } from '@/types/finance';
import { toast } from 'sonner';

export const useAttendance = () => {
  const queryClient = useQueryClient();

  const { data: todayAttendance, isLoading: isLoadingToday } = useQuery({
    queryKey: ['attendance-today'],
    queryFn: async () => {
      const { data } = await axios.get('/api/admin/attendance/today');
      return data as Attendance;
    },
  });

  const clockIn = useMutation({
    mutationFn: async (formData: FormData) => {
      const { data } = await axios.post('/api/admin/attendance/clock-in', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
      return data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['attendance-today'] });
      toast.success('Absen masuk berhasil!');
    },
    onError: (error: any) => {
      toast.error(error.response?.data?.message || 'Gagal absen masuk');
    }
  });

  const clockOut = useMutation({
    mutationFn: async (formData: FormData) => {
      const { data } = await axios.post('/api/admin/attendance/clock-out', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
      return data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['attendance-today'] });
      toast.success('Absen keluar berhasil!');
    },
    onError: (error: any) => {
      toast.error(error.response?.data?.message || 'Gagal absen keluar');
    }
  });

  return {
    todayAttendance,
    isLoadingToday,
    clockIn,
    clockOut,
  };
};
