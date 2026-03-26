"use client";

import { useState } from "react";
import { useLeads } from "@/hooks/use-leads";
import { 
  Card, 
  CardContent, 
  CardHeader, 
  CardTitle,
  CardDescription 
} from "@/components/ui/card";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { 
  Search, 
  Plus, 
  Filter, 
  MoreVertical,
  UserCheck,
  Phone,
  Mail,
  MapPin,
  Calendar
} from "lucide-react";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuHeader,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { LeadStatus } from "@/types/lead";
import { format } from "date-fns";
import { id } from "date-fns/locale";

const statusConfig: Record<LeadStatus, { label: string; color: string }> = {
  new: { label: "Baru", color: "bg-blue-500/10 text-blue-500" },
  contacted: { label: "Dihubungi", color: "bg-yellow-500/10 text-yellow-500" },
  qualified: { label: "Prospek", color: "bg-purple-500/10 text-purple-500" },
  proposal: { label: "Proposal", color: "bg-orange-500/10 text-orange-500" },
  negotiation: { label: "Negosiasi", color: "bg-cyan-500/10 text-cyan-500" },
  won: { label: "Closing", color: "bg-green-500/10 text-green-500" },
  lost: { label: "Gagal", color: "bg-red-500/10 text-red-500" },
};

export default function LeadsPage() {
  const [search, setSearch] = useState("");
  const { leadsQuery, updateStatusMutation } = useLeads();

  const leads = leadsQuery.data?.data || [];

  const handleUpdateStatus = (leadId: number, status: LeadStatus) => {
    updateStatusMutation.mutate({ id: leadId, status });
  };

  return (
    <div className="space-y-6">
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 className="text-3xl font-bold tracking-tight">CRM & Leads</h1>
          <p className="text-muted-foreground">
            Kelola prospek dan pantau pipeline penjualan Anda.
          </p>
        </div>
        <Button className="w-full md:w-auto gap-2">
          <Plus className="h-4 w-4" />
          Tambah Lead
        </Button>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
        <Card className="bg-blue-500/5 border-blue-500/20">
          <CardHeader className="pb-2">
            <CardDescription>Total Leads</CardDescription>
            <CardTitle className="text-2xl">{leadsQuery.data?.total || 0}</CardTitle>
          </CardHeader>
        </Card>
        <Card className="bg-yellow-500/5 border-yellow-500/20">
          <CardHeader className="pb-2">
            <CardDescription>Baru / Dihubungi</CardDescription>
            <CardTitle className="text-2xl">
              {leads.filter((l: any) => ['new', 'contacted'].includes(l.status)).length}
            </CardTitle>
          </CardHeader>
        </Card>
        <Card className="bg-green-500/5 border-green-500/20">
          <CardHeader className="pb-2">
            <CardDescription>Closed (Won)</CardDescription>
            <CardTitle className="text-2xl">
              {leads.filter((l: any) => l.status === 'won').length}
            </CardTitle>
          </CardHeader>
        </Card>
        <Card className="bg-purple-500/5 border-purple-500/20">
          <CardHeader className="pb-2">
            <CardDescription>Conversion Rate</CardDescription>
            <CardTitle className="text-2xl">
              {leadsQuery.data?.total > 0 
                ? Math.round((leads.filter((l: any) => l.status === 'won').length / leadsQuery.data.total) * 100) 
                : 0}%
            </CardTitle>
          </CardHeader>
        </Card>
      </div>

      <Card>
        <CardHeader>
          <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <CardTitle>Daftar Prospek</CardTitle>
            <div className="flex items-center gap-2">
              <div className="relative w-full md:w-64">
                <Search className="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                <Input
                  placeholder="Cari lead..."
                  className="pl-8"
                  value={search}
                  onChange={(e) => setSearch(e.target.value)}
                />
              </div>
              <Button variant="outline" size="icon">
                <Filter className="h-4 w-4" />
              </Button>
            </div>
          </div>
        </CardHeader>
        <CardContent>
          <div className="rounded-md border">
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>Lead</TableHead>
                  <TableHead>Kontak</TableHead>
                  <TableHead>Sumber</TableHead>
                  <TableHead>Sales</TableHead>
                  <TableHead>Status</TableHead>
                  <TableHead>Dibuat</TableHead>
                  <TableHead className="text-right">Aksi</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {leadsQuery.isLoading ? (
                  <TableRow>
                    <TableCell colSpan={7} className="h-24 text-center">
                      Loading leads...
                    </TableCell>
                  </TableRow>
                ) : leads.length === 0 ? (
                  <TableRow>
                    <TableCell colSpan={7} className="h-24 text-center">
                      Tidak ada lead ditemukan.
                    </TableCell>
                  </TableRow>
                ) : (
                  leads.map((lead: any) => (
                    <TableRow key={lead.id}>
                      <TableCell>
                        <div className="flex flex-col">
                          <span className="font-medium">{lead.name}</span>
                          <span className="text-xs text-muted-foreground">{lead.lead_number}</span>
                        </div>
                      </TableCell>
                      <TableCell>
                        <div className="flex flex-col gap-1">
                          <div className="flex items-center gap-2 text-xs">
                            <Phone className="h-3 w-3" />
                            {lead.phone || '-'}
                          </div>
                          <div className="flex items-center gap-2 text-xs">
                            <Mail className="h-3 w-3" />
                            {lead.email || '-'}
                          </div>
                        </div>
                      </TableCell>
                      <TableCell className="capitalize">{lead.source}</TableCell>
                      <TableCell>
                        <div className="flex items-center gap-2">
                          <div className="h-6 w-6 rounded-full bg-muted flex items-center justify-center">
                            <UserCheck className="h-3 w-3" />
                          </div>
                          <span className="text-sm font-medium">
                            {lead.assigned_sales?.name || 'Unassigned'}
                          </span>
                        </div>
                      </TableCell>
                      <TableCell>
                        <Badge variant="outline" className={statusConfig[lead.status as LeadStatus].color}>
                          {statusConfig[lead.status as LeadStatus].label}
                        </Badge>
                      </TableCell>
                      <TableCell>
                        <div className="flex items-center gap-2 text-xs">
                          <Calendar className="h-3 w-3" />
                          {format(new Date(lead.created_at), "dd MMM yyyy", { locale: id })}
                        </div>
                      </TableCell>
                      <TableCell className="text-right">
                        <DropdownMenu>
                          <DropdownMenuTrigger asChild>
                            <Button variant="ghost" size="icon">
                              <MoreVertical className="h-4 w-4" />
                            </Button>
                          </DropdownMenuTrigger>
                          <DropdownMenuContent align="end">
                            <DropdownMenuLabel>Update Status</DropdownMenuLabel>
                            <DropdownMenuItem onClick={() => handleUpdateStatus(lead.id, 'contacted')}>
                              Dihubungi
                            </DropdownMenuItem>
                            <DropdownMenuItem onClick={() => handleUpdateStatus(lead.id, 'qualified')}>
                              Qualified
                            </DropdownMenuItem>
                            <DropdownMenuItem onClick={() => handleUpdateStatus(lead.id, 'proposal')}>
                              Proposal
                            </DropdownMenuItem>
                            <DropdownMenuItem onClick={() => handleUpdateStatus(lead.id, 'won')}>
                              Won (Closing)
                            </DropdownMenuItem>
                            <DropdownMenuItem onClick={() => handleUpdateStatus(lead.id, 'lost')}>
                              Lost (Gagal)
                            </DropdownMenuItem>
                            <DropdownMenuSeparator />
                            <DropdownMenuItem>Lihat Detail</DropdownMenuItem>
                            <DropdownMenuItem className="text-red-600">Hapus</DropdownMenuItem>
                          </DropdownMenuContent>
                        </DropdownMenu>
                      </TableCell>
                    </TableRow>
                  ))
                )}
              </TableBody>
            </Table>
          </div>
        </CardContent>
      </Card>
    </div>
  );
}
