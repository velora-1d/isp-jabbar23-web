'use client';

import { useState, useEffect } from 'react';
import { useAuth } from '@/hooks/use-auth';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent } from '@/components/ui/card';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { Mail, Lock, Eye, EyeOff, Wifi, Users, Receipt, BarChart3 } from 'lucide-react';

export default function LoginPage() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [showPassword, setShowPassword] = useState(false);
  const { login, isLoggingIn, loginError } = useAuth();

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    login({ email, password });
  };

  return (
    <div className="relative min-h-screen flex bg-zinc-950 text-white overflow-hidden font-sans antialiased">
      {/* Background Decorations */}
      <div className="fixed inset-0 overflow-hidden pointer-events-none">
        <div className="absolute top-20 left-10 w-72 h-72 bg-cyan-500/10 rounded-full blur-[120px] animate-pulse" />
        <div className="absolute bottom-20 right-10 w-96 h-96 bg-teal-500/10 rounded-full blur-[120px] animate-pulse delay-1000" />
        <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-blue-500/5 rounded-full blur-[120px]" />
        
        {/* Subtle Grid Pattern */}
        <div className="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.01)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.01)_1px,transparent_1px)] bg-[size:60px_60px]" />
      </div>

      <div className="relative flex flex-1 w-full">
        {/* Left Side - Branding (Hidden on mobile) */}
        <div className="hidden lg:flex lg:w-1/2 items-center justify-center p-12 bg-zinc-900/50">
          <div className="max-w-lg space-y-12">
            {/* Logo */}
            <div className="flex items-center space-x-4">
              <div className="relative">
                <div className="absolute -inset-2 bg-gradient-to-r from-cyan-500 via-blue-500 to-teal-500 rounded-3xl blur-lg opacity-40" />
                <div className="relative p-4 rounded-2xl bg-gradient-to-br from-cyan-500 via-blue-600 to-teal-600 shadow-2xl shadow-cyan-500/20">
                  <Wifi className="w-10 h-10 text-white" />
                </div>
              </div>
              <div>
                <h1 className="text-4xl font-black bg-gradient-to-r from-cyan-400 via-blue-400 to-teal-400 bg-clip-text text-transparent leading-none">
                  <span className="typing-jabbar">JABBAR23</span>
                </h1>
                <p className="text-sm font-bold text-zinc-500 tracking-[0.2em] uppercase mt-1">
                  <span className="typing-isp">Internet Service Provider</span>
                </p>
              </div>
            </div>

            {/* Welcome Text */}
            <div className="space-y-2">
              <h2 className="text-3xl font-bold text-white">Selamat Datang di</h2>
              <p className="text-xl text-cyan-400 font-semibold">Panel Admin ISP</p>
            </div>

            {/* Features Preview */}
            <div className="space-y-6">
              {[
                { icon: Users, label: "Manajemen Pelanggan", color: "text-cyan-400", bg: "bg-cyan-500/10", border: "border-cyan-500/20" },
                { icon: Receipt, label: "Tagihan & Pembayaran", color: "text-blue-400", bg: "bg-blue-500/10", border: "border-blue-500/20" },
                { icon: BarChart3, label: "Laporan & Analytics", color: "text-teal-400", bg: "bg-teal-500/10", border: "border-teal-500/20" }
              ].map((feature, i) => (
                <div key={i} className="flex items-center text-zinc-400 group cursor-default transition-colors hover:text-white">
                  <div className={`w-10 h-10 rounded-xl ${feature.bg} border ${feature.border} flex items-center justify-center mr-4 transition-all duration-300 group-hover:scale-110`}>
                    <feature.icon className={`w-5 h-5 ${feature.color}`} />
                  </div>
                  <span className="font-medium">{feature.label}</span>
                </div>
              ))}
            </div>
          </div>
        </div>

        {/* Right Side - Login Form */}
        <div className="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 z-10">
          <div className="w-full max-w-md space-y-8">
            {/* Mobile Logo */}
            <div className="lg:hidden flex items-center justify-center space-x-3 mb-12">
              <div className="relative">
                <div className="absolute -inset-1.5 bg-gradient-to-r from-cyan-500 via-blue-500 to-teal-500 rounded-2xl blur-md opacity-50" />
                <div className="relative p-3 rounded-xl bg-gradient-to-br from-cyan-500 via-blue-600 to-teal-600">
                  <Wifi className="w-8 h-8 text-white" />
                </div>
              </div>
              <div className="flex flex-col">
                <span className="text-2xl font-black bg-gradient-to-r from-cyan-400 via-blue-400 to-teal-400 bg-clip-text text-transparent">
                  JABBAR23
                </span>
                <span className="text-[10px] font-bold text-zinc-500 tracking-[0.2em] uppercase">
                  ISP
                </span>
              </div>
            </div>

            {/* Login Card */}
            <div className="relative">
              <div className="absolute -inset-1 bg-gradient-to-r from-cyan-500/10 via-blue-500/10 to-teal-500/10 rounded-3xl blur-xl" />
              
              <Card className="relative glass-card border-zinc-800/50 rounded-3xl overflow-hidden">
                <CardContent className="p-8 sm:p-10">
                  <div className="text-center mb-10">
                    <h2 className="text-2xl sm:text-3xl font-bold text-white mb-2">Welcome Back</h2>
                    <p className="text-zinc-500">Masuk ke dashboard admin Anda</p>
                  </div>

                  <form onSubmit={handleSubmit} className="space-y-6">
                    {loginError && (
                      <Alert variant="destructive" className="border-red-900/50 bg-red-900/10 text-red-400 rounded-xl animate-in fade-in slide-in-from-top-2">
                        <AlertDescription>
                          Login gagal. Silakan periksa kembali kredensial Anda.
                        </AlertDescription>
                      </Alert>
                    )}

                    <div className="space-y-2">
                      <Label htmlFor="email" className="text-sm font-semibold text-zinc-400 ml-1">
                        Email Address
                      </Label>
                      <div className="relative group">
                        <div className="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                          <Mail className="w-5 h-5 text-zinc-600 group-focus-within:text-cyan-400 transition-colors" />
                        </div>
                        <Input
                          id="email"
                          type="email"
                          placeholder="admin@jabbar23.com"
                          value={email}
                          onChange={(e) => setEmail(e.target.value)}
                          required
                          autoFocus
                          className="w-full pl-12 pr-4 py-6 bg-zinc-950/50 border-zinc-800 rounded-xl text-white placeholder-zinc-700 focus:border-cyan-500/50 focus:ring-cyan-500/20 transition-all duration-300"
                        />
                      </div>
                    </div>

                    <div className="space-y-2">
                      <div className="flex items-center justify-between ml-1">
                        <Label htmlFor="password" className="text-sm font-semibold text-zinc-400">
                          Password
                        </Label>
                        <a href="#" className="text-xs text-cyan-400 hover:text-cyan-300 transition-colors">
                            Lupa password?
                        </a>
                      </div>
                      <div className="relative group">
                        <div className="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                          <Lock className="w-5 h-5 text-zinc-600 group-focus-within:text-cyan-400 transition-colors" />
                        </div>
                        <Input
                          id="password"
                          type={showPassword ? "text" : "password"}
                          placeholder="••••••••"
                          value={password}
                          onChange={(e) => setPassword(e.target.value)}
                          required
                          className="w-full pl-12 pr-12 py-6 bg-zinc-950/50 border-zinc-800 rounded-xl text-white placeholder-zinc-700 focus:border-cyan-500/50 focus:ring-cyan-500/20 transition-all duration-300"
                        />
                        <button
                          type="button"
                          onClick={() => setShowPassword(!showPassword)}
                          className="absolute inset-y-0 right-0 flex items-center pr-4 text-zinc-600 hover:text-cyan-400 transition-colors"
                        >
                          {showPassword ? <EyeOff className="w-5 h-5" /> : <Eye className="w-5 h-5" />}
                        </button>
                      </div>
                    </div>

                    <Button 
                      type="submit" 
                      className="relative w-full py-7 text-base font-bold text-white rounded-xl overflow-hidden group transition-all duration-300 shadow-lg shadow-cyan-500/20 hover:shadow-cyan-500/40 border-0" 
                      disabled={isLoggingIn}
                    >
                      <div className="absolute inset-0 bg-gradient-to-r from-cyan-600 via-blue-600 to-teal-600" />
                      <div className="absolute inset-0 bg-gradient-to-r from-cyan-500 via-blue-500 to-teal-500 opacity-0 group-hover:opacity-100 transition-opacity" />
                      <span className="relative flex items-center justify-center">
                        {isLoggingIn ? (
                          <span className="animate-pulse">Memproses...</span>
                        ) : (
                          <>
                            <Wifi className="w-5 h-5 mr-2" />
                            Masuk ke Dashboard
                          </>
                        )}
                      </span>
                    </Button>
                  </form>

                  <div className="mt-8 pt-6 border-t border-zinc-900/50">
                    <p className="text-center text-xs text-zinc-600">
                      PT Fakta Jabbar Industri &copy; {new Date().getFullYear()} JABBAR23 ISP. <br/>All rights reserved.
                    </p>
                  </div>
                </CardContent>
              </Card>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
