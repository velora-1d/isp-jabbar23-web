'use client';

import { useState, useEffect } from 'react';
import { useAuth } from '@/hooks/use-auth';
import {
  Eye, EyeOff, ArrowRight, Loader2, AlertCircle,
  Wifi, Users, Receipt, Activity, Shield,
  Network, Headphones, BarChart3, Package,
  CheckCircle2, Lock,
} from 'lucide-react';
import { cn } from '@/lib/utils';

// ─── Feature list kiri ────────────────────────────────────────────────────────
const features = [
  {
    icon: Users,
    title: 'CRM Pelanggan',
    desc: 'Kelola data pelanggan, kontrak, dan riwayat layanan secara terpusat.',
    accent: '#10B981',
    bg: '#111827',
    border: '#1f2937',
  },
  {
    icon: Receipt,
    title: 'Billing & Tagihan',
    desc: 'Otomasi tagihan bulanan, integrasi payment gateway, dan laporan keuangan.',
    accent: '#10B981',
    bg: '#111827',
    border: '#1f2937',
  },
  {
    icon: Network,
    title: 'Monitoring Jaringan',
    desc: 'Real-time monitoring OLT, ODP, PPPoE, dan status infrastruktur jaringan.',
    accent: '#10B981',
    bg: '#111827',
    border: '#1f2937',
  },
  {
    icon: Headphones,
    title: 'Ticketing Support',
    desc: 'Sistem tiket keluhan dengan SLA tracking dan eskalasi otomatis.',
    accent: '#10B981',
    bg: '#111827',
    border: '#1f2937',
  },
  {
    icon: BarChart3,
    title: 'Analytics & Laporan',
    desc: 'Dashboard insight pendapatan, churn pelanggan, dan performa jaringan.',
    accent: '#10B981',
    bg: '#111827',
    border: '#1f2937',
  },
  {
    icon: Package,
    title: 'Inventaris & Aset',
    desc: 'Tracking perangkat, stok material, dan manajemen aset jaringan.',
    accent: '#10B981',
    bg: '#111827',
    border: '#1f2937',
  },
];

const stats = [
  { label: 'Uptime', value: '99.9%',   icon: Activity, color: '#10B981' },
  { label: 'Pelanggan', value: '2.4K', icon: Users,    color: '#06B6D4' },
  { label: 'SLA',  value: '< 2j',      icon: Shield,   color: '#8B5CF6' },
];

// ─── Floating Label Input ─────────────────────────────────────────────────────
interface FloatInputProps {
  id: string; label: string; type?: string; value: string;
  onChange: (v: string) => void;
  icon: React.ReactNode; suffix?: React.ReactNode; autoFocus?: boolean;
}

function FloatInput({ id, label, type = 'text', value, onChange, icon, suffix, autoFocus }: FloatInputProps) {
  const [focused, setFocused] = useState(false);
  const lifted = focused || value.length > 0;
  return (
    <div className="relative group">
      <div className={cn(
        'relative flex items-center rounded-xl border transition-all duration-300 overflow-hidden',
        focused ? 'bg-white/[0.04] border-emerald-500/50 ring-4 ring-emerald-500/10' 
                : 'bg-white/[0.02] border-white/5 hover:border-white/10 hover:bg-white/[0.03]'
      )}>
        <div className={cn('pl-4 flex-shrink-0 transition-colors duration-300', focused ? 'text-emerald-400' : 'text-[#6b7280]')}>
          {icon}
        </div>
        <div className="relative flex-1 h-[52px]">
          <label htmlFor={id} className={cn(
            'absolute left-3 pointer-events-none transition-all duration-300 font-medium select-none',
            lifted ? 'top-[6px] text-[10px] tracking-[0.05em] text-[#9ca3af]'
                   : 'top-1/2 -translate-y-1/2 text-[13px] text-[#8e95a3]'
          )}>
            {label}
          </label>
          <input
            id={id} type={type} value={value} autoFocus={autoFocus}
            onChange={e => onChange(e.target.value)}
            onFocus={() => setFocused(true)}
            onBlur={() => setFocused(false)}
            placeholder=""
            autoComplete={id === 'email' ? 'email' : 'current-password'}
            className={cn(
              'absolute inset-x-3 bg-transparent text-white text-[14px] outline-none placeholder-transparent caret-emerald-500',
              lifted ? 'top-[22px] bottom-1' : 'top-1/2 -translate-y-1/2'
            )}
          />
        </div>
        {suffix && <div className="pr-3 flex-shrink-0">{suffix}</div>}
      </div>
    </div>
  );
}

// ─── Page ─────────────────────────────────────────────────────────────────────
export default function LoginPage() {
  const [email, setEmail]               = useState('');
  const [password, setPassword]         = useState('');
  const [showPassword, setShowPassword] = useState(false);
  const [mounted, setMounted]           = useState(false);
  const { login, isLoggingIn, loginError } = useAuth();

  useEffect(() => { const t = setTimeout(() => setMounted(true), 80); return () => clearTimeout(t); }, []);

  const handleSubmit = (e: React.FormEvent) => { e.preventDefault(); login({ email, password }); };

  return (
    <div className="relative min-h-screen flex items-center justify-center bg-[#0a0f1e] text-white overflow-hidden antialiased select-none font-sans">

      <div className="fixed inset-0 pointer-events-none">
        <div className="absolute top-[-20%] left-[-10%] w-[700px] h-[700px] rounded-full bg-emerald-600/[0.035] blur-[140px] animate-pulse duration-10000" />
        <div className="absolute bottom-[-20%] right-[-10%] w-[800px] h-[800px] rounded-full bg-cyan-600/[0.025] blur-[140px] animate-pulse duration-10000" />
        {/* Dot grid */}
        <div className="absolute inset-0"
          style={{ backgroundImage: 'radial-gradient(circle, rgba(255,255,255,0.03) 1px, transparent 1px)', backgroundSize: '32px 32px' }} />
        {/* Vignette */}
        <div className="absolute inset-0 bg-[radial-gradient(ellipse_100%_100%_at_50%_50%,transparent_30%,#0a0f1e_100%)]" />
      </div>

      {/* ── Main layout ───────────────────────────────────────────────────── */}
      <div className="relative flex flex-col md:flex-row w-full min-h-screen items-stretch max-w-[1600px] mx-auto z-10">

        {/* ═══════════════════════════════════════════════════════════════════
            LEFT PANEL — Feature Showcase
        ═══════════════════════════════════════════════════════════════════ */}
        <div className="flex flex-col items-center md:items-end justify-center w-full md:w-1/2 px-6 py-10 md:py-6 md:pr-[12%] md:pl-8 overflow-hidden">
          
          <div className="w-full max-w-[580px] flex flex-col justify-center gap-8 h-full">
            {/* Logo */}
            <div className={cn('transition-all duration-700', mounted ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-3')}>
              <div className="flex items-center gap-3.5">
                <div className="relative">
                  <div className="absolute -inset-2.5 rounded-2xl blur-xl" style={{ background: 'linear-gradient(135deg,#10B981,#06B6D4)', opacity: 0.35 }} />
                  <div className="relative p-3 rounded-2xl" style={{ background: 'linear-gradient(135deg,#10B981,#06B6D4)' }}>
                    <Wifi className="w-6 h-6 text-white" strokeWidth={2.5} />
                  </div>
                </div>
                <div>
                  <p className="text-xl font-black tracking-tight" style={{ background: 'linear-gradient(90deg,#34D399,#22D3EE)', WebkitBackgroundClip: 'text', WebkitTextFillColor: 'transparent' }}>
                    JABBAR23
                  </p>
                  <p className="text-[9px] font-bold tracking-[0.3em] uppercase text-zinc-600 mt-0.5">
                    Internet Service Provider
                  </p>
                </div>
              </div>
            </div>

            {/* Headline */}
            <div className={cn('transition-all duration-700 delay-75', mounted ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4')}>
              <p className="text-[11px] font-bold tracking-[0.2em] uppercase text-[#10b981] mb-4 flex items-center gap-2">
                <span className="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse" /> Mission Control Platform
              </p>
              <h2 className="text-[42px] font-[800] leading-[1.1] text-white mb-6 max-w-lg tracking-tight">
                Satu Panel untuk
                <br />
                <span className="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-cyan-400">
                  Seluruh Operasi ISP
                </span>
              </h2>
              <p className="text-zinc-400 text-[16px] leading-relaxed max-w-md font-medium">
                Dari CRM hingga monitoring jaringan — semua terintegrasi dalam satu platform manajemen ISP yang powerful.
              </p>
            </div>

            {/* ── Feature Cards GRID dengan OVERLAP ──────────────────────────── */}
            <div className={cn('transition-all duration-700 delay-150', mounted ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6')}>
              <div className="grid grid-cols-2 xl:grid-cols-3 gap-3 max-w-2xl">
                {features.map((f, i) => (
                  <div
                    key={i}
                    className={cn(
                      'relative flex flex-col group cursor-default p-[20px] transition-all duration-300 h-full',
                      'bg-white/[0.02] border border-white/[0.05] hover:border-emerald-500/30 hover:bg-white/[0.04] backdrop-blur-md',
                      'hover:-translate-y-1 hover:shadow-[0_8px_30px_rgba(16,185,129,0.1)]',
                      'rounded-2xl overflow-hidden'
                    )}
                  >
                    {/* Inner stroke for 3D depth */}
                    <div className="absolute inset-0 rounded-2xl ring-1 ring-inset ring-white/5 pointer-events-none" />

                    {/* Glow hover */}
                    <div
                      className="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"
                      style={{ background: `radial-gradient(circle at 50% 0%, ${f.accent}15, transparent 70%)` }}
                    />
                    {/* Icon */}
                    <div className="relative mb-4 w-10 h-10 rounded-xl flex items-center justify-center shrink-0 ring-1 ring-white/10 shadow-lg"
                      style={{ background: `linear-gradient(135deg, ${f.accent}25, transparent)` }}>
                      <f.icon className="w-5 h-5 text-white/90" style={{ filter: `drop-shadow(0 2px 8px ${f.accent}60)` }} />
                    </div>
                    <div className="flex flex-col flex-1 relative z-10">
                      <p className="font-semibold text-[14px] text-white mb-1.5 tracking-wide">{f.title}</p>
                      <p className="text-[12px] text-zinc-400 leading-relaxed pr-2">{f.desc}</p>
                    </div>

                    {/* Check badge */}
                    <div className="absolute top-4 right-4">
                      <CheckCircle2 className="w-3.5 h-3.5" style={{ color: f.accent, opacity: 0.3 }} />
                    </div>
                  </div>
                ))}
              </div>
            </div>

            {/* ── Stats bar bottom ────────────────────────────────────────────── */}
            <div className={cn('flex flex-wrap items-center mt-6 pt-4 border-t border-[#1f2937] transition-all duration-700 delay-200', mounted ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4')}>
              {stats.map((s, i) => (
                <div key={i} className="flex flex-row items-center">
                  <div className="flex items-center gap-3 pr-6">
                    <s.icon className="w-4 h-4" style={{ color: s.color }} />
                    <div>
                      <p className="text-base font-black leading-none" style={{ color: s.color }}>{s.value}</p>
                      <p className="text-[10px] text-zinc-600 mt-1 font-medium tracking-wide uppercase">{s.label}</p>
                    </div>
                  </div>
                  {i < stats.length - 1 && (
                    <div className="w-px h-8 bg-[#1f2937] mx-4" />
                  )}
                </div>
              ))}
            </div>
          </div>
        </div>

        {/* ═══════════════════════════════════════════════════════════════════
            OVERLAP DIVIDER — elemen yang melewati batas panel
        ═══════════════════════════════════════════════════════════════════ */}
        <div className="hidden md:block absolute left-1/2 top-0 bottom-0 -translate-x-1/2 z-20 pointer-events-none">
          {/* Vertical glowing line */}
          <div className="w-px h-full"
            style={{ background: 'linear-gradient(to bottom, transparent, rgba(16,185,129,0.3) 15%, rgba(16,185,129,0.6) 50%, rgba(16,185,129,0.3) 85%, transparent)' }} />
          {/* Center pulse dot */}
          <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
            <div className="w-2.5 h-2.5 rounded-full animate-pulse"
              style={{ background: 'linear-gradient(135deg,#10B981,#06B6D4)', boxShadow: '0 0 24px rgba(16,185,129,0.8)' }} />
          </div>
        </div>

        {/* ═══════════════════════════════════════════════════════════════════
            RIGHT PANEL — Login Form
        ═══════════════════════════════════════════════════════════════════ */}
        <div className="w-full md:w-1/2 relative flex flex-col items-center md:items-start justify-center px-5 py-10 md:py-6 md:pl-[15%] md:pr-8">

          {/* Right panel bg — subtle glassmorphism */}
          <div className="absolute inset-0 hidden md:block"
            style={{
              background: 'linear-gradient(135deg, rgba(255,255,255,0.015), transparent)',
              backdropFilter: 'blur(40px)',
            }}
          />

          {/* Decorative overlap blob dari kanan panel ke kiri */}
          <div className="absolute -left-32 top-1/2 -translate-y-1/2 w-[400px] h-[400px] rounded-full pointer-events-none hidden md:block"
            style={{ background: 'radial-gradient(circle, rgba(16,185,129,0.06) 0%, transparent 60%)' }} />

          <div className={cn(
            'relative z-10 w-full max-w-[480px] flex flex-col transition-all duration-700 delay-100',
            mounted ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'
          )}>

            {/* Mobile logo */}
            <div className="md:hidden flex items-center gap-3 mb-10">
              <div className="relative">
                <div className="absolute -inset-2 rounded-xl blur-xl opacity-40"
                  style={{ background: 'linear-gradient(135deg,#10B981,#06B6D4)' }} />
                <div className="relative p-2.5 rounded-xl"
                  style={{ background: 'linear-gradient(135deg,#10B981,#06B6D4)' }}>
                  <Wifi className="w-5 h-5 text-white" />
                </div>
              </div>
              <span className="text-xl font-black"
                style={{ background: 'linear-gradient(90deg,#34D399,#22D3EE)', WebkitBackgroundClip: 'text', WebkitTextFillColor: 'transparent' }}>
                JABBAR23
              </span>
            </div>

            {/* ── Card login dengan overlap shadow ── */}
            <div className="relative flex flex-col w-full">
              {/* Shadow overlap card di belakang */}
              <div className="absolute -inset-4 rounded-3xl pointer-events-none hidden md:block"
                style={{
                  background: 'linear-gradient(135deg, rgba(16,185,129,0.06), rgba(6,182,212,0.04))',
                  filter: 'blur(24px)',
                  transform: 'scale(0.96) translateY(8px)',
                }} />
              {/* Shadow card layer 2 — overlap effect */}
              <div className="absolute inset-0 translate-x-2 translate-y-3 rounded-2xl md:rounded-3xl pointer-events-none hidden md:block"
                style={{ background: 'rgba(16,185,129,0.04)', border: '1px solid rgba(16,185,129,0.08)' }} />

              {/* Main card */}
              <div className="relative rounded-[20px] w-full px-9 py-10 flex flex-col border border-white/[0.06] shadow-2xl"
                style={{
                  background: 'linear-gradient(145deg, rgba(24, 24, 27, 0.6) 0%, rgba(9, 9, 11, 0.8) 100%)',
                  backdropFilter: 'blur(40px)',
                  boxShadow: '0 32px 80px rgba(0,0,0,0.6)',
                }}>
                <div className="absolute inset-0 rounded-[20px] ring-1 ring-inset ring-white/5 pointer-events-none" />

                {/* 1. Badge "ADMIN PANEL" — margin bottom 16px */}
                <span className="inline-flex items-center self-start gap-1.5 text-[10px] font-bold tracking-[0.2em] uppercase text-emerald-400 mb-4">
                  <span className="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse" />
                  Admin Panel
                </span>

                {/* 2. "Selamat Datang" heading — margin bottom 4px */}
                <h1 className="text-[28px] font-[700] text-white leading-tight mb-1">
                  Selamat Datang
                </h1>

                {/* 3. subtitle text — margin bottom 28px */}
                <p className="text-sm text-zinc-500 mb-7">
                  Masuk untuk mengakses Mission Control
                </p>

                {/* Error */}
                {loginError && (
                  <div className="flex items-start gap-3 p-3.5 mb-5 rounded-xl animate-in fade-in slide-in-from-top-2 duration-300"
                    style={{ background: 'rgba(239,68,68,0.06)', border: '1px solid rgba(239,68,68,0.2)' }}>
                    <AlertCircle className="h-4 w-4 text-rose-400 flex-shrink-0 mt-0.5" />
                    <div>
                      <p className="text-xs font-bold text-rose-300">Autentikasi Gagal</p>
                      <p className="text-[11px] text-rose-400/70 mt-0.5">Periksa kembali email dan password.</p>
                    </div>
                  </div>
                )}

                {/* Form */}
                <form onSubmit={handleSubmit} className="flex flex-col">
                  {/* 4. Email input — margin bottom 12px */}
                  <div className="mb-3">
                    <FloatInput
                      id="email" label="Email Address" type="email"
                      value={email} onChange={setEmail} autoFocus
                      icon={
                        <svg className="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.8}>
                          <path strokeLinecap="round" strokeLinejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                      }
                    />
                  </div>

                  {/* 5. Password input — margin bottom 8px */}
                  <div className="mb-2">
                    <FloatInput
                      id="password" label="Password" type={showPassword ? 'text' : 'password'}
                      value={password} onChange={setPassword}
                      icon={
                        <svg className="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.8}>
                          <path strokeLinecap="round" strokeLinejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                      }
                      suffix={
                        <button type="button" tabIndex={-1}
                          onClick={() => setShowPassword(v => !v)}
                          className="p-1.5 text-zinc-600 hover:text-zinc-300 transition-colors rounded-lg">
                          {showPassword ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
                        </button>
                      }
                    />
                  </div>

                  {/* 6. "Lupa password?" — margin bottom 24px */}
                  <div className="flex justify-end mb-6">
                    <a href="#" className="text-[13px] font-medium text-[#10b981] hover:text-[#059669] transition-colors">
                      Lupa password?
                    </a>
                  </div>

                  {/* 7. Button "Masuk ke Dashboard" — margin bottom 24px */}
                  <button
                    type="submit"
                    disabled={isLoggingIn || !email || !password}
                    className="group relative w-full flex items-center justify-center gap-2.5 h-[52px] rounded-xl font-semibold text-[15px] text-white overflow-hidden transition-all duration-300 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 mb-6 border border-emerald-500/20"
                    style={{
                      background: 'linear-gradient(135deg, rgba(16,185,129,0.9), rgba(6,182,212,0.9))',
                      boxShadow: '0 8px 32px rgba(16,185,129,0.25), inset 0 1px 1px rgba(255,255,255,0.2)',
                    }}
                  >
                    {/* Hover overlay */}
                    <div className="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                      style={{ background: 'linear-gradient(135deg, #10B981, #06B6D4)' }} />
                    {/* Shine sweep */}
                    <div className="absolute inset-0 -translate-x-full group-hover:translate-x-[150%] transition-transform duration-1000 ease-out"
                      style={{ background: 'linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent)', transform: 'skewX(-20deg)' }} />
                    <span className="relative flex items-center gap-2 drop-shadow-sm">
                      {isLoggingIn ? (
                        <><Loader2 className="w-5 h-5 animate-spin text-white" /><span>Mengautentikasi...</span></>
                      ) : (
                        <><span>Masuk ke Dashboard</span><ArrowRight className="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" /></>
                      )}
                    </span>
                  </button>
                </form>

                {/* 8. Divider 1px — margin bottom 16px */}
                <div className="h-px bg-[#1f2937] w-full mb-4" />

                {/* 9. Trust badges (shield + lock icons) — margin bottom 24px */}
                <div className="flex flex-col gap-2 mb-6">
                  <div className="flex items-center gap-2 text-[12px] text-[#4b5563]">
                    <Shield className="w-3.5 h-3.5" /> <span>Digunakan oleh 2.4K+ pelanggan aktif</span>
                  </div>
                  <div className="flex items-center gap-2 text-[12px] text-[#4b5563]">
                    <Lock className="w-3.5 h-3.5" /> <span>Koneksi aman &amp; terenkripsi</span>
                  </div>
                </div>

                {/* 10. Footer "PT Fakta Jabbar" */}
                <div>
                  <p className="text-center text-[11px] text-[#374151] leading-relaxed">
                    PT Fakta Jabbar Industri &copy; 2026&ensp;&middot;&ensp;JABBAR23 ISP
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
