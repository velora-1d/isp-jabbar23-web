'use client';

import { useState } from 'react';
import { useContracts } from '@/hooks/use-contracts';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { FileText, Plus, UserCheck, Calendar, Info } from 'lucide-react';
import { format } from 'date-fns';
import { id as localeId } from 'date-fns/locale';
import { SignaturePad } from '@/components/ui/signature-pad';
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogDescription,
} from '@/components/ui/dialog';

interface ContractSectionProps {
  customerId: number;
}

export function ContractSection({ customerId }: ContractSectionProps) {
  const { contracts, isLoading, createContract, signContract } = useContracts(customerId);
  const [isSigning, setIsSigning] = useState<number | null>(null);

  const handleCreateDraft = () => {
    createContract.mutate({
      customer_id: customerId,
      start_date: format(new Date(), 'yyyy-MM-dd'),
      terms: 'Layanan internet broadband JABBAR23. S&K berlaku.',
    });
  };

  if (isLoading) return <div>Memuat data kontrak...</div>;

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div className="space-y-1">
          <h3 className="text-lg font-medium">Digital Contract</h3>
          <p className="text-sm text-zinc-500">Kelola kontrak dan tanda tangan digital pelanggan.</p>
        </div>
        <Button onClick={handleCreateDraft} disabled={createContract.isPending}>
          <Plus className="w-4 h-4 mr-2" />
          Buat Draf Kontrak
        </Button>
      </div>

      {contracts && contracts.length > 0 ? (
        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
          {contracts.map((contract) => (
            <Card key={contract.id} className="overflow-hidden glass-card">
              <CardHeader className="pb-2">
                <div className="flex items-center justify-between">
                  <Badge variant={contract.status === 'active' ? 'default' : 'secondary'}>
                    {contract.status.toUpperCase()}
                  </Badge>
                  <span className="text-xs text-zinc-500 font-mono">{contract.contract_number}</span>
                </div>
                <CardTitle className="text-base flex items-center gap-2 mt-2">
                  <FileText className="w-4 h-4 text-primary" />
                  Internet Subscription Contract
                </CardTitle>
              </CardHeader>
              <CardContent className="space-y-4 pt-2">
                <div className="grid grid-cols-2 gap-2 text-sm text-zinc-600 dark:text-zinc-400">
                  <div className="flex items-center gap-2">
                    <Calendar className="w-3.5 h-3.5" />
                    Mulai: {format(new Date(contract.start_date), 'dd MMM yyyy', { locale: localeId })}
                  </div>
                  {contract.signed_at && (
                    <div className="flex items-center gap-2 text-green-600 dark:text-green-400">
                      <UserCheck className="w-3.5 h-3.5" />
                      Ttd: {format(new Date(contract.signed_at), 'dd/MM/yy HH:mm')}
                    </div>
                  )}
                </div>

                {contract.status === 'draft' && (
                  <Button 
                    className="w-full mt-2" 
                    variant="outline"
                    onClick={() => setIsSigning(contract.id)}
                  >
                    Tanda Tangani Sekarang
                  </Button>
                )}

                {contract.digital_signature_path && (
                  <div className="mt-2 pt-4 border-t border-dashed">
                    <p className="text-[10px] text-zinc-400 uppercase mb-2">Digital Signature Hash Verified</p>
                    <div className="h-12 w-32 bg-white/50 dark:bg-black/20 rounded flex items-center justify-center grayscale opacity-70">
                       <img 
                        src={`http://localhost:8000/storage/${contract.digital_signature_path}`} 
                        alt="Signature" 
                        className="max-h-full object-contain"
                       />
                    </div>
                    <p className="text-[9px] text-zinc-500 mt-1">IP: {contract.client_ip}</p>
                  </div>
                )}
              </CardContent>
            </Card>
          ))}
        </div>
      ) : (
        <Card className="border-dashed flex flex-col items-center justify-center p-12 text-center space-y-4">
          <div className="w-12 h-12 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
            <Info className="w-6 h-6 text-zinc-400" />
          </div>
          <div className="space-y-1">
            <h4 className="font-medium">Belum ada kontrak</h4>
            <p className="text-sm text-zinc-500">Klik tombol "Buat Draf Kontrak" untuk memulai.</p>
          </div>
        </Card>
      )}

      {/* Signature Modal */}
      <Dialog open={isSigning !== null} onOpenChange={(open) => !open && setIsSigning(null)}>
        <DialogContent className="sm:max-w-xl">
          <DialogHeader>
            <DialogTitle>Penandatanganan Kontrak Digital</DialogTitle>
            <DialogDescription>
              Silakan bubuhkan tanda tangan Anda pada area di bawah ini sebagai persetujuan layanan.
            </DialogDescription>
          </DialogHeader>
          {isSigning && (
            <SignaturePad
              onCancel={() => setIsSigning(null)}
              onSave={(signature) => {
                signContract.mutate({ id: isSigning, signature }, {
                  onSuccess: () => setIsSigning(null)
                });
              }}
            />
          )}
        </DialogContent>
      </Dialog>
    </div>
  );
}
