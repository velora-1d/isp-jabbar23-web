'use client';

import React, { use } from 'react';
import { useRouter } from 'next/navigation';
import { CustomerForm } from '@/components/customers/customer-form';
import { useCustomer, useUpdateCustomer } from '@/hooks/use-customers';
import { toast } from 'sonner';
import { ChevronLeft, Loader2 } from 'lucide-react';
import { Button } from '@/components/ui/button';

export default function EditCustomerPage({ params }: { params: Promise<{ id: string }> }) {
  const { id } = use(params);
  const router = useRouter();
  const { data: customer, isLoading } = useCustomer(id);
  const updateCustomer = useUpdateCustomer();

  const handleSubmit = async (data: any) => {
    // Convert 'none' values back to null for API
    const payload = { ...data };
    if (payload.assigned_to === 'none') payload.assigned_to = null;
    if (payload.olt_id === 'none') payload.olt_id = null;

    await updateCustomer.mutateAsync({ id, data: payload }, {
      onSuccess: () => {
        toast.success('Data pelanggan berhasil diperbarui!');
        router.push(`/dashboard/admin/customers/${id}`);
      },
    });
  };

  if (isLoading) {
    return (
      <div className="flex flex-col items-center justify-center min-h-[600px] space-y-4">
        <Loader2 className="w-10 h-10 animate-spin text-blue-500" />
        <p className="text-gray-400 text-sm animate-pulse">Mengambil data pelanggan...</p>
      </div>
    );
  }

  if (!customer) {
    return (
      <div className="p-6 text-center text-gray-400">
        Pelanggan tidak ditemukan.
      </div>
    );
  }

  // Pre-process data for the form
  const initialData = {
    ...customer,
    package_id: customer.package?.id?.toString() || '',
    assigned_to: customer.technician?.id?.toString() || 'none',
    olt_id: customer.olt?.id?.toString() || 'none',
    // Format dates for input[type="date"]
    installation_date: customer.created_at ? customer.created_at.split('T')[0] : '',
    // Adjust other dates if needed
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
            <h1 className="text-2xl font-bold text-white tracking-tight">Edit Pelanggan</h1>
            <p className="text-sm text-gray-400">Update informasi untuk {customer.name} ({customer.customer_id})</p>
          </div>
        </div>
      </div>

      <CustomerForm 
        initialData={initialData}
        onSubmit={handleSubmit} 
        isLoading={updateCustomer.isPending} 
      />
    </div>
  );
}
