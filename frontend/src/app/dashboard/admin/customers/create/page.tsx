'use client';

import React from 'react';
import { useRouter } from 'next/navigation';
import { CustomerForm } from '@/components/customers/customer-form';
import { useCreateCustomer } from '@/hooks/use-customers';
import { toast } from 'sonner';
import { ChevronLeft } from 'lucide-react';
import { Button } from '@/components/ui/button';

export default function CreateCustomerPage() {
  const router = useRouter();
  const createCustomer = useCreateCustomer();

  const handleSubmit = async (data: any) => {
    // Convert 'none' values back to null for API
    const payload = { ...data };
    if (payload.assigned_to === 'none') payload.assigned_to = null;
    if (payload.olt_id === 'none') payload.olt_id = null;

    await createCustomer.mutateAsync(payload, {
      onSuccess: () => {
        toast.success('Pelanggan berhasil didaftarkan!');
        router.push('/dashboard/admin/customers');
      },
    });
  };

  return (
    <div className="p-6 max-w-5xl mx-auto space-y-6">
      <div className="flex items-center justify-between">
        <div className="flex items-center space-x-4">
          <Button 
            variant="ghost" 
            size="icon" 
            onClick={() => router.back()}
            className="rounded-full hover:bg-gray-800"
          >
            <ChevronLeft className="w-5 h-5 text-gray-400" />
          </Button>
          <div>
            <h1 className="text-2xl font-bold text-white tracking-tight">Daftar Pelanggan Baru</h1>
            <p className="text-sm text-gray-400">Masukkan detail informasi pelanggan untuk aktivasi layanan.</p>
          </div>
        </div>
      </div>

      <CustomerForm 
        onSubmit={handleSubmit} 
        isLoading={createCustomer.isPending} 
      />
    </div>
  );
}
