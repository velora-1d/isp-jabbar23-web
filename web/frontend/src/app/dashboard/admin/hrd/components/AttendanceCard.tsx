'use client';

import { useState, useEffect, useRef } from 'react';
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Camera, MapPin, CheckCircle2, AlertCircle } from 'lucide-react';
import { useMutation } from '@tanstack/react-query';
import axios from '@/lib/axios';
import { toast } from 'sonner';

export default function AttendanceCard() {
  const [step, setStep] = useState<'initial' | 'camera' | 'confirm'>('initial');
  const [photo, setPhoto] = useState<string | null>(null);
  const [location, setLocation] = useState<{ lat: number; lng: number } | null>(null);
  const videoRef = useRef<HTMLVideoElement>(null);
  const canvasRef = useRef<HTMLCanvasElement>(null);

  const startCamera = async () => {
    try {
      const stream = await navigator.mediaDevices.getUserMedia({ video: true });
      if (videoRef.current) {
        videoRef.current.srcObject = stream;
        setStep('camera');
      }
    } catch (err) {
      toast.error('Gagal mengakses kamera. Pastikan izin diberikan.');
    }
  };

  const takePhoto = () => {
    if (videoRef.current && canvasRef.current) {
      const context = canvasRef.current.getContext('2d');
      if (context) {
        canvasRef.current.width = videoRef.current.videoWidth;
        canvasRef.current.height = videoRef.current.videoHeight;
        context.drawImage(videoRef.current, 0, 0);
        setPhoto(canvasRef.current.toDataURL('image/jpeg'));
        
        // Stop camera
        const stream = videoRef.current.srcObject as MediaStream;
        stream.getTracks().forEach(track => track.stop());
        
        setStep('confirm');
        getLocation();
      }
    }
  };

  const getLocation = () => {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        (pos) => setLocation({ lat: pos.coords.latitude, lng: pos.coords.longitude }),
        () => toast.error('Gagal mendapatkan lokasi. Pastikan GPS aktif.')
      );
    }
  };

  const attendanceMutation = useMutation({
    mutationFn: async () => {
      return await axios.post('/api/admin/attendances/check-in', {
        photo,
        latitude: location?.lat,
        longitude: location?.lng
      });
    },
    onSuccess: () => {
      toast.success('Berhasil melakukan absensi!');
      setStep('initial');
      setPhoto(null);
    },
    onError: (error: any) => {
      toast.error(error.response?.data?.message || 'Gagal melakukan absensi.');
    }
  });

  return (
    <Card className="border-zinc-800 bg-zinc-900 text-zinc-100 overflow-hidden group">
      <div className="absolute inset-0 bg-gradient-to-br from-blue-600/5 to-transparent pointer-events-none" />
      <CardHeader>
        <div className="flex items-center justify-between">
          <div>
            <CardTitle className="flex items-center gap-2">
              <CheckCircle2 className="h-5 w-5 text-blue-500" />
              Absensi Karyawan
            </CardTitle>
            <CardDescription className="text-zinc-400">
              Absen masuk/pulang dengan GPS & Selfie
            </CardDescription>
          </div>
          {location && (
            <div className="flex items-center gap-1 text-[10px] text-zinc-500 bg-zinc-800/50 px-2 py-1 rounded-full border border-zinc-700">
              <MapPin className="h-3 w-3" />
              Lokasi Aktif
            </div>
          )}
        </div>
      </CardHeader>
      <CardContent>
        {step === 'initial' && (
          <div className="flex flex-col items-center justify-center py-6 space-y-4">
            <div className="h-20 w-20 rounded-full bg-blue-500/10 flex items-center justify-center border border-blue-500/20 group-hover:scale-110 transition-transform">
              <Camera className="h-10 w-10 text-blue-400" />
            </div>
            <Button onClick={startCamera} className="bg-blue-600 hover:bg-blue-500 text-white w-full rounded-xl">
              Absen Sekarang
            </Button>
          </div>
        )}

        {step === 'camera' && (
          <div className="space-y-4">
            <div className="relative aspect-video rounded-xl bg-black border border-zinc-800 overflow-hidden">
              <video ref={videoRef} autoPlay className="w-full h-full object-cover" />
              <canvas ref={canvasRef} className="hidden" />
            </div>
            <Button onClick={takePhoto} className="w-full bg-blue-600 text-white rounded-xl">
              Ambil Foto Selfie
            </Button>
          </div>
        )}

        {step === 'confirm' && (
          <div className="space-y-4">
            {photo && (
              <div className="relative aspect-video rounded-xl border border-zinc-800 overflow-hidden">
                <img src={photo} alt="Selfie" className="w-full h-full object-cover" />
                <div className="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                   <Button variant="ghost" size="sm" onClick={() => setStep('initial')} className="text-white hover:text-red-400">
                      Ulangi
                   </Button>
                </div>
              </div>
            )}
            <div className="space-y-2">
               {!location && (
                 <div className="text-xs text-yellow-500 flex items-center gap-2 p-2 bg-yellow-500/10 rounded-lg border border-yellow-500/20">
                   <AlertCircle className="h-4 w-4" />
                   Menunggu koordinat GPS...
                 </div>
               )}
               <Button 
                 onClick={() => attendanceMutation.mutate()} 
                 disabled={!location || attendanceMutation.isPending}
                 className="w-full bg-green-600 hover:bg-green-500 text-white rounded-xl"
               >
                 {attendanceMutation.isPending ? 'Mengirim...' : 'Konfirmasi Kehadiran'}
               </Button>
            </div>
          </div>
        )}
      </CardContent>
    </Card>
  );
}
