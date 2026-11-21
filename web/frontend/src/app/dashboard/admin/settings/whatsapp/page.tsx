"use client";

import { useState, useEffect } from "react";
import axios from "axios";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { Label } from "@/components/ui/label";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Switch } from "../../../../../components/ui/switch";
import { toast } from "sonner";
import { MessageSquare, Save, Smartphone, Zap } from "lucide-react";

export default function WhatsappSettingsPage() {
  const [loading, setLoading] = useState(false);
  const [config, setConfig] = useState<{
    provider: string;
    baseUrl: string;
    apiKey: string;
    autoSendInvoice: boolean;
    autoSendReceipt: boolean;
  }>({
    provider: "fonnte",
    baseUrl: "https://api.fonnte.com",
    apiKey: "",
    autoSendInvoice: true,
    autoSendReceipt: true,
  });

  useEffect(() => {
    fetchSettings();
  }, []);

  const fetchSettings = async () => {
    try {
      const response = await axios.get("/api/admin/settings?group=notification");
      const data = response.data as Record<string, string | undefined>;
      if (data) {
        setConfig({
          provider: data.whatsapp_provider || "fonnte",
          baseUrl: data.whatsapp_base_url || "https://api.fonnte.com",
          apiKey: data.whatsapp_api_key || "",
          autoSendInvoice: data.auto_send_invoice === "1",
          autoSendReceipt: data.auto_send_receipt === "1",
        });
      }
    } catch (error) {
      console.error("Failed to fetch settings", error);
    }
  };

  const handleSave = async () => {
    setLoading(true);
    try {
      await axios.post("/api/admin/settings?group=notification", {
        whatsapp_provider: config.provider,
        whatsapp_base_url: config.baseUrl,
        whatsapp_api_key: config.apiKey,
        auto_send_invoice: config.autoSendInvoice ? "1" : "0",
        auto_send_receipt: config.autoSendReceipt ? "1" : "0",
      });
      toast.success("Pengaturan WhatsApp berhasil disimpan");
    } catch (error) {
      toast.error("Gagal menyimpan pengaturan");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="p-6 space-y-6 max-w-4xl mx-auto">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold tracking-tight bg-gradient-to-r from-green-400 to-emerald-600 bg-clip-text text-transparent">
            WhatsApp Integration
          </h1>
          <p className="text-muted-foreground">
            Konfigurasi gateway dan otomatisasi notifikasi pelanggan.
          </p>
        </div>
        <Button onClick={handleSave} disabled={loading} className="bg-emerald-600 hover:bg-emerald-700">
          <Save className="w-4 h-4 mr-2" />
          Simpan Perubahan
        </Button>
      </div>

      <div className="grid gap-6 md:grid-cols-2">
        <Card className="border-emerald-500/20 bg-emerald-500/5 backdrop-blur-sm">
          <CardHeader>
            <CardTitle className="flex items-center gap-2">
              <Smartphone className="w-5 h-5 text-emerald-500" />
              Gateway Provider
            </CardTitle>
            <CardDescription>Pilih provider WhatsApp yang digunakan.</CardDescription>
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="space-y-2">
              <Label>Provider</Label>
              <Select 
                value={config.provider as string} 
                onValueChange={(v) => setConfig({...config, provider: v ?? "", baseUrl: (v ?? 'fonnte') === 'fonnte' ? 'https://api.fonnte.com' : 'http://localhost:3000'})}
              >
                <SelectTrigger>
                  <SelectValue placeholder="Pilih Provider" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="fonnte">Fonnte (Cloud API)</SelectItem>
                  <SelectItem value="waha">WAHA (Self-hosted)</SelectItem>
                </SelectContent>
              </Select>
            </div>
            <div className="space-y-2">
              <Label>API Base URL</Label>
              <Input 
                value={config.baseUrl} 
                onChange={(e) => setConfig({...config, baseUrl: e.target.value})}
                placeholder="https://api.example.com" 
              />
            </div>
            <div className="space-y-2">
              <Label>API Key / Token</Label>
              <Input 
                type="password"
                value={config.apiKey}
                onChange={(e) => setConfig({...config, apiKey: e.target.value})}
                placeholder="Masukkan API Key" 
              />
            </div>
          </CardContent>
        </Card>

        <Card className="border-blue-500/20 bg-blue-500/5 backdrop-blur-sm">
          <CardHeader>
            <CardTitle className="flex items-center gap-2">
              <Zap className="w-5 h-5 text-blue-500" />
              Otomatisasi
            </CardTitle>
            <CardDescription>Aturan pengiriman pesan otomatis.</CardDescription>
          </CardHeader>
          <CardContent className="space-y-6">
            <div className="flex items-center justify-between">
              <div className="space-y-0.5">
                <Label>Kirim Invoice Baru</Label>
                <p className="text-xs text-muted-foreground">Kirim pesan saat invoice bulanan terbit.</p>
              </div>
              <Switch 
                checked={config.autoSendInvoice}
                onCheckedChange={(v: boolean) => setConfig({...config, autoSendInvoice: v})}
              />
            </div>
            <div className="flex items-center justify-between">
              <div className="space-y-0.5">
                <Label>Kirim Tanda Terima</Label>
                <p className="text-xs text-muted-foreground">Kirim pesan saat pembayaran dikonfirmasi.</p>
              </div>
              <Switch 
                checked={config.autoSendReceipt}
                onCheckedChange={(v: boolean) => setConfig({...config, autoSendReceipt: v})}
              />
            </div>
          </CardContent>
        </Card>
      </div>

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <MessageSquare className="w-5 h-5 text-purple-500" />
            Template Pesan (Preview)
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div className="grid gap-4 md:grid-cols-2">
            <div className="p-4 rounded-lg bg-muted border text-sm font-mono whitespace-pre-wrap">
              {`Halo {name},\n\nTagihan internet Jabbar23 Anda periode {period} telah terbit.\nNomor: {invoice_no}\nTotal: {amount}\n\nTerima kasih.`}
            </div>
            <div className="p-4 rounded-lg bg-muted border text-sm font-mono whitespace-pre-wrap">
              {`Terima kasih {name}!\n\nPembayaran invoice {invoice_no} sebesar {amount} telah kami terima.\n\nSalam, Jabbar23.`}
            </div>
          </div>
        </CardContent>
      </Card>
    </div>
  );
}
