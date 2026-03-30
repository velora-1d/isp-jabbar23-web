"use client";

import { useTicket, useUpdateTicket, useTickets } from "@/hooks/use-tickets";
import { useParams, useRouter } from "next/navigation";
import { 
  Card, 
  CardContent, 
  CardHeader, 
  CardTitle,
  CardDescription
} from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import { 
  Select, 
  SelectContent, 
  SelectItem, 
  SelectTrigger, 
  SelectValue 
} from "@/components/ui/select";
import { 
  ArrowLeft, 
  User, 
  Calendar, 
  Tag, 
  Info, 
  MessageSquare, 
  Save,
  Loader2,
  ExternalLink,
  ChevronRight
} from "lucide-react";
import Link from "next/link";
import { format } from "date-fns";
import { id as idLocale } from "date-fns/locale";
import { useState, useEffect } from "react";

export default function TicketDetailPage() {
  const { id } = useParams() as { id: string };
  const router = useRouter();
  const { data: ticket, isLoading } = useTicket(id);
  const { data: ticketOptions } = useTickets(); // To get options like technicians
  const updateTicket = useUpdateTicket();

  const [form, setForm] = useState<{
    status: string;
    priority: string;
    technician_id: string;
    admin_notes: string;
  }>({
    status: "",
    priority: "",
    technician_id: "",
    admin_notes: "",
  });

  useEffect(() => {
    if (ticket) {
      setForm({
        status: ticket.status,
        priority: ticket.priority,
        technician_id: ticket.technician_id || "unassigned",
        admin_notes: ticket.admin_notes || "",
      });
    }
  }, [ticket]);

  if (isLoading) {
    return (
      <div className="flex items-center justify-center min-h-[400px]">
        <Loader2 className="w-8 h-8 animate-spin text-blue-600" />
      </div>
    );
  }

  if (!ticket) return <div>Tiket tidak ditemukan.</div>;

  const handleUpdate = () => {
    updateTicket.mutate({
      id,
      data: {
        ...form,
        technician_id: form.technician_id === "unassigned" ? null : form.technician_id,
      } as any
    });
  };

  const getStatusBadge = (status: string) => {
    switch (status) {
      case "open":
        return <Badge className="bg-blue-500/10 text-blue-500 border-blue-500/20">Open</Badge>;
      case "in_progress":
        return <Badge className="bg-yellow-500/10 text-yellow-500 border-yellow-500/20">In Progress</Badge>;
      case "resolved":
        return <Badge className="bg-green-500/10 text-green-500 border-green-500/20">Resolved</Badge>;
      case "closed":
        return <Badge variant="secondary">Closed</Badge>;
      default:
        return <Badge variant="outline">{status}</Badge>;
    }
  };

  return (
    <div className="flex flex-col gap-6 p-6">
      <div className="flex items-center justify-between">
        <div className="flex items-center gap-4">
          <Button variant="ghost" size="icon" asChild className="rounded-full">
            <Link href="/dashboard/admin/tickets">
              <ArrowLeft className="h-5 w-5" />
            </Link>
          </Button>
          <div className="flex flex-col">
            <h1 className="text-2xl font-bold tracking-tight flex items-center gap-2">
              {ticket.ticket_number}
              {getStatusBadge(ticket.status)}
            </h1>
            <p className="text-muted-foreground flex items-center gap-2">
              Dibuat pada {ticket.created_at ? format(new Date(ticket.created_at), "dd MMMM yyyy, HH:mm", { locale: idLocale }) : "-"}
            </p>
          </div>
        </div>
        <div className="flex items-center gap-2">
           <Button 
            className="bg-blue-600 hover:bg-blue-700 text-white shadow-lg shadow-blue-500/20"
            onClick={handleUpdate}
            disabled={updateTicket.isPending}
           >
            {updateTicket.isPending ? <Loader2 className="w-4 h-4 mr-2 animate-spin" /> : <Save className="w-4 h-4 mr-2" />}
            Simpan Perubahan
          </Button>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* Main Content */}
        <div className="lg:col-span-2 flex flex-col gap-6">
          <Card className="border-none shadow-md overflow-hidden bg-white dark:bg-slate-950">
            <CardHeader className="bg-slate-50/50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-800">
              <CardTitle className="text-lg font-bold flex items-center gap-2">
                <Info className="w-5 h-5 text-blue-500" />
                Detail Gangguan
              </CardTitle>
            </CardHeader>
            <CardContent className="p-6 space-y-6">
              <div className="space-y-2">
                <Label className="text-xs uppercase tracking-wider text-muted-foreground">Subjek</Label>
                <p className="text-xl font-semibold">{ticket.subject}</p>
              </div>
              
              <div className="space-y-2">
                <Label className="text-xs uppercase tracking-wider text-muted-foreground">Deskripsi Masalah</Label>
                <div className="bg-slate-50/50 dark:bg-slate-900/50 p-4 rounded-lg border border-slate-100 dark:border-slate-800">
                  <p className="whitespace-pre-wrap">{ticket.description}</p>
                </div>
              </div>

              {ticket.evidence_photo && (
                <div className="space-y-2">
                   <Label className="text-xs uppercase tracking-wider text-muted-foreground">Foto Bukti</Label>
                   <div className="relative group rounded-lg overflow-hidden border border-slate-100 max-w-md">
                      <img 
                        src={`/storage/${ticket.evidence_photo}`} 
                        alt="Evidence" 
                        className="w-full h-auto object-cover transition-transform group-hover:scale-105"
                      />
                      <div className="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <Button variant="outline" className="text-white border-white hover:bg-white/20" asChild>
                           <a href={`/storage/${ticket.evidence_photo}`} target="_blank" rel="noopener noreferrer">
                             <ExternalLink className="w-4 h-4 mr-2" /> Lihat Full
                           </a>
                        </Button>
                      </div>
                   </div>
                </div>
              )}
            </CardContent>
          </Card>

          <Card className="border-none shadow-md overflow-hidden bg-white dark:bg-slate-950">
            <CardHeader className="bg-slate-50/50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-800">
              <CardTitle className="text-lg font-bold flex items-center gap-2">
                <MessageSquare className="w-5 h-5 text-blue-500" />
                Catatan Penanganan (Admin/Teknisi)
              </CardTitle>
            </CardHeader>
            <CardContent className="p-6">
              <Textarea 
                placeholder="Tuliskan catatan teknis atau histori penanganan di sini..."
                className="min-h-[150px] border-slate-200 focus:ring-blue-500"
                value={form.admin_notes}
                onChange={(e) => setForm({ ...form, admin_notes: e.target.value })}
              />
              <p className="mt-2 text-xs text-muted-foreground italic">
                *Catatan ini hanya dapat dilihat oleh admin dan teknisi.
              </p>
            </CardContent>
          </Card>
        </div>

        {/* Sidebar Controls */}
        <div className="flex flex-col gap-6">
          <Card className="border-none shadow-md overflow-hidden bg-white dark:bg-slate-950">
            <CardHeader className="bg-slate-50/50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-800">
              <CardTitle className="text-lg font-bold flex items-center gap-2 font-mono">
                PENGATURAN TIKET
              </CardTitle>
            </CardHeader>
            <CardContent className="p-6 space-y-6">
              <div className="space-y-2">
                <Label>Status Sistem</Label>
                <Select value={form.status} onValueChange={(v) => setForm({ ...form, status: v ?? "" })}>
                  <SelectTrigger className="border-slate-200 h-11">
                    <SelectValue placeholder="Pilih Status" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="open">Open (Antrian)</SelectItem>
                    <SelectItem value="in_progress">In Progress (Sedang Dikerjakan)</SelectItem>
                    <SelectItem value="resolved">Resolved (Selesai)</SelectItem>
                    <SelectItem value="closed">Closed (Dibatalkan/Tutup)</SelectItem>
                  </SelectContent>
                </Select>
              </div>

              <div className="space-y-2">
                <Label>Prioritas</Label>
                <Select value={form.priority} onValueChange={(v) => setForm({ ...form, priority: v ?? "" })}>
                  <SelectTrigger className="border-slate-200 h-11">
                    <SelectValue placeholder="Pilih Prioritas" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="low">Low</SelectItem>
                    <SelectItem value="medium">Medium</SelectItem>
                    <SelectItem value="high">High</SelectItem>
                    <SelectItem value="critical">Critical</SelectItem>
                  </SelectContent>
                </Select>
              </div>

              <div className="space-y-2">
                <Label>Teknisi Penanggung Jawab</Label>
                <Select 
                  value={form.technician_id} 
                  onValueChange={(v) => setForm({ ...form, technician_id: v ?? "" })}
                >
                  <SelectTrigger className="border-slate-200 h-11">
                    <SelectValue placeholder="Pilih Teknisi" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="unassigned">Belum Ditugaskan</SelectItem>
                    {ticketOptions?.options?.technicians.map((tech: { id: string; name: string }) => (
                      <SelectItem key={tech.id} value={tech.id.toString()}>
                        {tech.name}
                      </SelectItem>
                    ))}
                  </SelectContent>
                </Select>
              </div>
            </CardContent>
          </Card>

          <Card className="border-none shadow-md overflow-hidden bg-white dark:bg-slate-950">
            <CardHeader className="bg-slate-50/50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-800">
              <CardTitle className="text-lg font-bold flex items-center gap-2">
                <User className="w-5 h-5 text-blue-500" />
                Informasi Pelanggan
              </CardTitle>
            </CardHeader>
            <CardContent className="p-6 space-y-4">
              <div className="flex items-center gap-3">
                <div className="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 font-bold">
                  {ticket.customer?.name.charAt(0)}
                </div>
                <div className="flex flex-col">
                   <p className="font-bold">{ticket.customer?.name}</p>
                   <p className="text-xs text-muted-foreground font-mono">{ticket.customer?.customer_id}</p>
                </div>
              </div>
              <Button variant="outline" className="w-full border-slate-200" asChild>
                <Link href={`/dashboard/admin/customers/${ticket.customer_id}`}>
                   Detail Pelanggan <ChevronRight className="w-4 h-4 ml-1" />
                </Link>
              </Button>
            </CardContent>
          </Card>
        </div>
      </div>
    </div>
  );
}
