'use client';

import React from 'react';
import { useRouter } from 'next/navigation';
import { CustomerForm } from '@/components/customers/customer-form';
import { useCreateCustomer } from '@/hooks/use-customers';
import { toast } from 'sonner';
import { DashboardPageShell } from '@/components/dashboard/page-shell';
import { UserPlus } from 'lucide-react';

export default function CreateCustomerPage() {
  const router = useRouter();
  const createCustomer = useCreateCustomer();

  const handleSubmit = async (data: any) => {
    // Normalisasi data untuk API
    const payload = { ...data };
    if (payload.assigned_to === 'none') payload.assigned_to = null;
    if (payload.olt_id === 'none') payload.olt_id = null;

    try {
      await createCustomer.mutateAsync(payload);
      toast.success('Pelanggan berhasil didaftarkan!');
      router.push('/dashboard/admin/customers');
    } catch (error: any) {
      // Error handling is managed in the form component generally, 
      // but we catch it here just in case.
    }
  };

  return (
    <DashboardPageShell
      title="Registrasi Pelanggan"
      description="Daftarkan pelanggan baru ke dalam sistem untuk aktivasi layanan internet."
      icon={UserPlus}
    >
      <div className="max-w-5xl mx-auto">
        <CustomerForm 
          onSubmit={handleSubmit} 
          isLoading={createCustomer.isPending} 
        />
      </div>
    </DashboardPageShell>
  );
}
