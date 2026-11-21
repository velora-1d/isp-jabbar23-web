'use client';

import { useState, useRef, useEffect } from 'react';
import { useAttendance } from '@/hooks/use-attendance';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { MapPin, Camera, Play, Square, CheckCircle2, AlertCircle, Clock } from 'lucide-react';
import { format } from 'date-fns';

export function AttendanceCard() {
  const { todayAttendance, clockIn, clockOut, isLoadingToday } = useAttendance();
  const [location, setLocation] = useState<string | null>(null);
  const [isCameraOpen, setIsCameraOpen] = useState(false);
  const videoRef = useRef<HTMLVideoElement>(null);
  const canvasRef = useRef<HTMLCanvasElement>(null);

  useEffect(() => {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition((pos) => {
        setLocation(`${pos.coords.latitude},${pos.coords.longitude}`);
      }, (err) => console.error(err));
    }
  }, []);

  const handleStartCamera = async () => {
    setIsCameraOpen(true);
    try {
      const stream = await navigator.mediaDevices.getUserMedia({ video: true });
      if (videoRef.current) {
        videoRef.current.srcObject = stream;
      }
    } catch (err) {
      console.error("Gagal akses kamera", err);
    }
  };

  const handleCapture = (type: 'in' | 'out') => {
    if (canvasRef.current && videoRef.current) {
      const context = canvasRef.current.getContext('2d');
      if (context) {
        canvasRef.current.width = videoRef.current.videoWidth;
        canvasRef.current.height = videoRef.current.videoHeight;
        context.drawImage(videoRef.current, 0, 0);
        
        canvasRef.current.toBlob((blob) => {
          if (blob) {
            const formData = new FormData();
            formData.append('photo', blob, 'attendance.jpg');
            formData.append('location', location || '');
            
            if (type === 'in') clockIn.mutate(formData);
            else clockOut.mutate(formData);
            
            // Close camera
            const stream = videoRef.current?.srcObject as MediaStream;
            stream?.getTracks().forEach(track => track.stop());
            setIsCameraOpen(false);
          }
        }, 'image/jpeg');
      }
    }
  };

  if (isLoadingToday) return <div>Memuat status absensi...</div>;

  return (
    <Card className="bg-zinc-900/40 border-zinc-800 overflow-hidden">
      <CardHeader className="bg-gradient-to-br from-zinc-900 to-black border-b border-zinc-800/50">
        <div className="flex items-center justify-between">
            <div className="space-y-1">
                <CardTitle className="text-lg">Absensi Karyawan</CardTitle>
                <CardDescription>Format: GPS + Selfie Verification</CardDescription>
            </div>
            <div className="text-right">
                <p className="text-xl font-bold font-mono tracking-tighter">{format(new Date(), 'HH:mm:ss')}</p>
                <p className="text-[10px] text-zinc-500 uppercase font-bold">{format(new Date(), 'EEEE, dd MMM yyyy')}</p>
            </div>
        </div>
      </CardHeader>
      <CardContent className="p-6 space-y-6">
        {isCameraOpen ? (
          <div className="space-y-4">
             <div className="relative aspect-video bg-black rounded-2xl overflow-hidden border border-zinc-800 group">
                <video ref={videoRef} autoPlay playsInline className="w-full h-full object-cover" />
                <canvas ref={canvasRef} className="hidden" />
                <div className="absolute inset-x-0 bottom-4 flex justify-center gap-4">
                   <Button variant="destructive" onClick={() => setIsCameraOpen(false)}>Batal</Button>
                   <Button variant="default" className="bg-emerald-500 hover:bg-emerald-600" onClick={() => handleCapture(!todayAttendance ? 'in' : 'out')}>
                      Ambil Foto & Absen
                   </Button>
                </div>
             </div>
             <p className="text-[10px] text-center text-zinc-500 uppercase tracking-widest animate-pulse">
                Menunggu Sinyal GPS: {location || 'Searching...'}
             </p>
          </div>
        ) : (
          <div className="grid grid-cols-2 gap-4">
             <div className="space-y-4">
                <div className="p-4 rounded-2xl bg-zinc-950/50 border border-zinc-800">
                    <p className="text-[10px] text-zinc-500 font-bold uppercase mb-2">Masuk</p>
                    {todayAttendance?.clock_in ? (
                        <div className="flex items-center gap-2 text-emerald-400 font-bold">
                            <CheckCircle2 className="w-4 h-4" />
                            {todayAttendance.clock_in}
                        </div>
                    ) : (
                        <Button variant="outline" size="sm" className="w-full border-zinc-700 h-8" onClick={handleStartCamera}>
                            <Play className="w-3 h-3 mr-2 text-emerald-400" />
                            Clock In
                        </Button>
                    )}
                </div>
             </div>
             <div className="space-y-4">
                <div className="p-4 rounded-2xl bg-zinc-950/50 border border-zinc-800">
                    <p className="text-[10px] text-zinc-500 font-bold uppercase mb-2">Keluar</p>
                    {todayAttendance?.clock_out ? (
                        <div className="flex items-center gap-2 text-blue-400 font-bold">
                            <CheckCircle2 className="w-4 h-4" />
                            {todayAttendance.clock_out}
                        </div>
                    ) : (
                        <Button variant="outline" size="sm" className="w-full border-zinc-700 h-8" 
                            disabled={!todayAttendance?.clock_in}
                            onClick={handleStartCamera}
                        >
                            <Square className="w-3 h-3 mr-2 text-red-400" />
                            Clock Out
                        </Button>
                    )}
                </div>
             </div>
          </div>
        )}

        <div className="p-4 bg-zinc-900/40 rounded-xl border border-zinc-800/50 flex items-start gap-4">
            <div className="p-2 rounded-lg bg-zinc-800/50 text-zinc-400">
                <MapPin className="w-4 h-4" />
            </div>
            <div>
                <p className="text-[11px] font-bold text-zinc-300">Lokasi Terdeteksi</p>
                <p className="text-[10px] text-zinc-500 mt-0.5 line-clamp-1">{location || 'Gagal mendeteksi lokasi (pastikan izin lokasi aktif)'}</p>
            </div>
        </div>
      </CardContent>
    </Card>
  );
}
