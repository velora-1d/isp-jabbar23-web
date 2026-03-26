<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full sm:translate-x-0 border-r border-gray-700/50 bg-gradient-to-b from-gray-900 via-gray-800 to-gray-900" aria-label="Sidebar" 
   x-data="{ open: localStorage.getItem('sidebarOpen') || '' }"
   x-init="
      // Restore scroll position on load
      $nextTick(() => {
         const savedScroll = localStorage.getItem('sidebarScroll');
         if (savedScroll) {
            $refs.sidebarContent.scrollTop = parseInt(savedScroll);
         }
      });
   ">
   <div x-ref="sidebarContent" 
        @scroll="localStorage.setItem('sidebarScroll', $el.scrollTop)" 
        class="h-full px-3 pb-4 overflow-y-auto scrollbar-hide" 
        style="-ms-overflow-style: none; scrollbar-width: none;">
      <style>.scrollbar-hide::-webkit-scrollbar { display: none; }</style>
      <ul class="space-y-2 font-medium">
         
         {{-- Dashboard - ALL ROLES --}}
         <x-menu-item route="dashboard" label="Beranda" color-from="blue-500" color-to="cyan-500">
            <x-slot:icon><svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 22 21"><path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/><path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/></svg></x-slot:icon>
         </x-menu-item>

         {{-- CRM & Sales - super-admin, admin, sales --}}
         @hasanyrole('super-admin|admin|sales')
         <li class="px-2 pt-4 pb-1">
            <button @click="open = open === 'crm' ? '' : 'crm'; localStorage.setItem('sidebarOpen', open)" class="w-full flex items-center justify-between text-gray-500 hover:text-gray-300 transition">
               <span class="text-xs font-semibold uppercase tracking-wider">CRM & Penjualan</span>
               <svg class="w-4 h-4 transition-transform" :class="open === 'crm' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
         </li>
         <div x-show="open === 'crm'" x-collapse>
            <x-menu-item route="leads.index" label="Prospek/Leads" color-from="cyan-500" color-to="teal-500">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg></x-slot:icon>
            </x-menu-item>
            <x-menu-item route="customers.index" label="Pelanggan" color-from="sky-500" color-to="blue-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg></x-slot:icon>
            </x-menu-item>
            <x-menu-item route="contracts.index" label="Kontrak" color-from="indigo-500" color-to="purple-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></x-slot:icon>
            </x-menu-item>
            <x-menu-item route="partners.index" label="Mitra/Reseller" color-from="teal-500" color-to="emerald-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg></x-slot:icon>
            </x-menu-item>
         </div>
         @endhasanyrole

         {{-- Billing & Finance - super-admin, admin, finance --}}
         @hasanyrole('super-admin|admin|finance')
         <li class="px-2 pt-4 pb-1">
            <button @click="open = open === 'billing' ? '' : 'billing'; localStorage.setItem('sidebarOpen', open)" class="w-full flex items-center justify-between text-gray-500 hover:text-gray-300 transition">
               <span class="text-xs font-semibold uppercase tracking-wider">Keuangan & Tagihan</span>
               <svg class="w-4 h-4 transition-transform" :class="open === 'billing' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
         </li>
         <div x-show="open === 'billing'" x-collapse>
            <x-menu-item route="invoices.index" label="Tagihan (Invoice)" color-from="purple-500" color-to="indigo-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></x-slot:icon>
            </x-menu-item>
            <x-menu-item route="payments.index" label="Pembayaran" color-from="blue-500" color-to="indigo-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg></x-slot:icon>
            </x-menu-item>
            <x-menu-item route="billing.recurring" label="Tagihan Otomatis" color-from="violet-500" color-to="purple-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></x-slot:icon>
            </x-menu-item>
            <x-menu-item route="billing.proforma" label="Proforma Invoice" color-from="fuchsia-500" color-to="pink-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg></x-slot:icon>
            </x-menu-item>
            <x-menu-item route="billing.credit-notes" label="Nota Kredit" color-from="rose-500" color-to="red-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg></x-slot:icon>
            </x-menu-item>
            <x-menu-item route="reports.index" label="Laporan Keuangan" color-from="pink-500" color-to="rose-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></x-slot:icon>
            </x-menu-item>
            @hasanyrole('super-admin|finance')
            <x-menu-item route="settings.payment-gateways" label="Metode Pembayaran" color-from="cyan-500" color-to="blue-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg></x-slot:icon>
            </x-menu-item>
            @endhasanyrole
         </div>
         @endhasanyrole

         {{-- Network - super-admin, admin, noc --}}
         @hasanyrole('super-admin|admin|noc')
         <li class="px-2 pt-4 pb-1">
            <button @click="open = open === 'network' ? '' : 'network'; localStorage.setItem('sidebarOpen', open)" class="w-full flex items-center justify-between text-gray-500 hover:text-gray-300 transition">
               <span class="text-xs font-semibold uppercase tracking-wider">Jaringan (Network)</span>
               <svg class="w-4 h-4 transition-transform" :class="open === 'network' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
         </li>
         <div x-show="open === 'network'" x-collapse>
            <x-menu-item route="network.monitoring.index" label="Monitor (Ping)" color-from="blue-500" color-to="cyan-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></x-slot:icon>
            </x-menu-item>
            <x-menu-item route="network.olts.index" label="Manajemen OLT" color-from="cyan-500" color-to="teal-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg></x-slot:icon>
            </x-menu-item>
            <x-menu-item route="network.odps.index" label="Manajemen ODP" color-from="teal-500" color-to="emerald-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg></x-slot:icon>
            </x-menu-item>
            <x-menu-item route="network.routers.index" label="Routers/Mikrotik" color-from="emerald-500" color-to="green-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg></x-slot:icon>
            </x-menu-item>
            <x-menu-item route="network.ipam.index" label="Manajemen IP" color-from="green-500" color-to="lime-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></x-slot:icon>
            </x-menu-item>
            <x-menu-item route="network.bandwidth.index" label="Bandwidth" color-from="lime-500" color-to="yellow-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg></x-slot:icon>
            </x-menu-item>
            <x-menu-item route="network.topology.index" label="Peta Topologi" color-from="yellow-500" color-to="amber-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg></x-slot:icon>
            </x-menu-item>
         </div>
         @endhasanyrole

         {{-- Support - super-admin, admin, sales, noc --}}
         @hasanyrole('super-admin|admin|sales|noc')
         <li class="px-2 pt-4 pb-1">
            <button @click="open = open === 'support' ? '' : 'support'; localStorage.setItem('sidebarOpen', open)" class="w-full flex items-center justify-between text-gray-500 hover:text-gray-300 transition">
               <span class="text-xs font-semibold uppercase tracking-wider">Dukungan (Support)</span>
               <svg class="w-4 h-4 transition-transform" :class="open === 'support' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
         </li>
         <div x-show="open === 'support'" x-collapse>
            <x-menu-item route="tickets.index" label="Tiket Kendala" color-from="red-500" color-to="orange-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg></x-slot:icon>
            </x-menu-item>
            @hasanyrole('super-admin|admin|sales')
            <x-menu-item route="messages.index" label="Pesan Pelanggan" color-from="orange-500" color-to="amber-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg></x-slot:icon>
            </x-menu-item>
            @endhasanyrole
            <x-menu-item route="knowledge-base.index" label="Pusat Bantuan" color-from="amber-500" color-to="yellow-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg></x-slot:icon>
            </x-menu-item>
            @hasanyrole('super-admin|admin|noc')
            <x-menu-item route="sla.index" label="Manajemen SLA" color-from="yellow-500" color-to="lime-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></x-slot:icon>
            </x-menu-item>
            @endhasanyrole
         </div>
         @endhasanyrole

         {{-- Field Operations - super-admin, admin, noc --}}
         @hasanyrole('super-admin|admin|noc')
         <li class="px-2 pt-4 pb-1">
            <button @click="open = open === 'field' ? '' : 'field'; localStorage.setItem('sidebarOpen', open)" class="w-full flex items-center justify-between text-gray-500 hover:text-gray-300 transition">
               <span class="text-xs font-semibold uppercase tracking-wider">Operasional Lapangan</span>
               <svg class="w-4 h-4 transition-transform" :class="open === 'field' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
         </li>
         <div x-show="open === 'field'" x-collapse>
            <x-menu-item route="technicians.index" label="Data Teknisi" color-from="emerald-500" color-to="teal-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 18"><path d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z"/></svg></x-slot:icon>
            </x-menu-item>
            <x-menu-item route="work-orders.index" label="Perintah Kerja (WO)" color-from="lime-500" color-to="green-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg></x-slot:icon>
            </x-menu-item>
            <x-menu-item route="scheduling.index" label="Jadwal Penugasan" color-from="green-500" color-to="emerald-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></x-slot:icon>
            </x-menu-item>
            <x-menu-item route="tracking.index" label="Pelacakan GPS" color-from="teal-500" color-to="cyan-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg></x-slot:icon>
            </x-menu-item>
            <x-menu-item route="installation-reports.index" label="Laporan Instalasi" color-from="cyan-500" color-to="blue-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></x-slot:icon>
            </x-menu-item>
         </div>
         @endhasanyrole

         {{-- Inventory - super-admin, admin, warehouse, finance (PO only) --}}
         @hasanyrole('super-admin|admin|warehouse|finance')
         <li class="px-2 pt-4 pb-1">
            <button @click="open = open === 'inventory' ? '' : 'inventory'; localStorage.setItem('sidebarOpen', open)" class="w-full flex items-center justify-between text-gray-500 hover:text-gray-300 transition">
               <span class="text-xs font-semibold uppercase tracking-wider">Gudang & Aset</span>
               <svg class="w-4 h-4 transition-transform" :class="open === 'inventory' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
         </li>
         <div x-show="open === 'inventory'" x-collapse>
            @hasanyrole('super-admin|admin|warehouse')
            <x-menu-item route="inventory.index" label="Stok Barang" color-from="yellow-500" color-to="amber-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg></x-slot:icon>
            </x-menu-item>
            <x-menu-item route="assets.index" label="Aset Tetap" color-from="amber-500" color-to="orange-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></x-slot:icon>
            </x-menu-item>
            <x-menu-item route="vendors.index" label="Vendor/Supplier" color-from="orange-500" color-to="red-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg></x-slot:icon>
            </x-menu-item>
            @endhasanyrole
            <x-menu-item route="purchase-orders.index" label="Pesanan Pembelian (PO)" color-from="red-500" color-to="rose-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg></x-slot:icon>
            </x-menu-item>
         </div>
         @endhasanyrole

         {{-- HRD - super-admin, admin, hrd, finance (payroll only) --}}
         @hasanyrole('super-admin|admin|hrd|finance')
         <li class="px-2 pt-4 pb-1">
            <button @click="open = open === 'hrd' ? '' : 'hrd'; localStorage.setItem('sidebarOpen', open)" class="w-full flex items-center justify-between text-gray-500 hover:text-gray-300 transition">
               <span class="text-xs font-semibold uppercase tracking-wider">SDM & Personalia</span>
               <svg class="w-4 h-4 transition-transform" :class="open === 'hrd' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
         </li>
         <div x-show="open === 'hrd'" x-collapse>
            @hasanyrole('super-admin|admin|hrd')
            <x-menu-item route="users.index" label="Data Pegawai" color-from="rose-500" color-to="pink-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg></x-slot:icon>
            </x-menu-item>
            <x-menu-item route="attendance.index" label="Absensi" color-from="pink-500" color-to="fuchsia-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"/></svg></x-slot:icon>
            </x-menu-item>
            @endhasanyrole
            <x-menu-item route="payroll.index" label="Penggajian (Payroll)" color-from="fuchsia-500" color-to="purple-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg></x-slot:icon>
            </x-menu-item>
            @hasanyrole('super-admin|admin|hrd')
            <x-menu-item route="leave.index" label="Cuti & Izin" color-from="purple-500" color-to="indigo-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></x-slot:icon>
            </x-menu-item>
            @endhasanyrole
         </div>
         @endhasanyrole

         {{-- Reports - super-admin, admin, finance --}}
         @hasanyrole('super-admin|admin|finance')
         <li class="px-2 pt-4 pb-1">
            <button @click="open = open === 'reports' ? '' : 'reports'; localStorage.setItem('sidebarOpen', open)" class="w-full flex items-center justify-between text-gray-500 hover:text-gray-300 transition">
               <span class="text-xs font-semibold uppercase tracking-wider">Laporan</span>
               <svg class="w-4 h-4 transition-transform" :class="open === 'reports' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
         </li>
         <div x-show="open === 'reports'" x-collapse>
            <x-menu-item route="reports.revenue" label="Laporan Pendapatan" color-from="emerald-500" color-to="teal-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2-1.343-2-3-2zM12 6v2m0 8v2m-6-6h2m8 0h2"/></svg></x-slot:icon>
            </x-menu-item>
            <x-menu-item route="reports.customers" label="Laporan Pelanggan" color-from="blue-500" color-to="indigo-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg></x-slot:icon>
            </x-menu-item>
            <x-menu-item route="reports.network" label="Laporan Jaringan" color-from="cyan-500" color-to="blue-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"/></svg></x-slot:icon>
            </x-menu-item>
            <x-menu-item route="reports.commissions" label="Laporan Komisi" color-from="amber-500" color-to="orange-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg></x-slot:icon>
            </x-menu-item>
         </div>
         @endhasanyrole

         {{-- Marketing - super-admin, admin, sales --}}
         @hasanyrole('super-admin|admin|sales')
         <li class="px-2 pt-4 pb-1">
            <button @click="open = open === 'marketing' ? '' : 'marketing'; localStorage.setItem('sidebarOpen', open)" class="w-full flex items-center justify-between text-gray-500 hover:text-gray-300 transition">
               <span class="text-xs font-semibold uppercase tracking-wider">Pemasaran</span>
               <svg class="w-4 h-4 transition-transform" :class="open === 'marketing' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
         </li>
         <div x-show="open === 'marketing'" x-collapse>
            <x-menu-item route="campaigns.index" label="Kampanye" color-from="violet-500" color-to="purple-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg></x-slot:icon>
            </x-menu-item>
            <x-menu-item route="promotions.index" label="Promosi/Voucher" color-from="fuchsia-500" color-to="pink-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg></x-slot:icon>
            </x-menu-item>
            <x-menu-item route="referrals.index" label="Program Referral" color-from="pink-500" color-to="rose-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg></x-slot:icon>
            </x-menu-item>
         </div>
         @endhasanyrole

         {{-- Settings - super-admin, admin --}}
         @hasanyrole('super-admin|admin')
         <li class="px-2 pt-4 pb-1">
            <button @click="open = open === 'settings' ? '' : 'settings'; localStorage.setItem('sidebarOpen', open)" class="w-full flex items-center justify-between text-gray-500 hover:text-gray-300 transition">
               <span class="text-xs font-semibold uppercase tracking-wider">Pengaturan</span>
               <svg class="w-4 h-4 transition-transform" :class="open === 'settings' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
         </li>
         <div x-show="open === 'settings'" x-collapse>
            <x-menu-item route="packages.index" label="Paket Langganan" color-from="amber-500" color-to="orange-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M18 5h-.7c.229-.467.349-.98.351-1.5a3.5 3.5 0 0 0-3.5-3.5c-1.717 0-3.215 1.2-4.331 2.481C8.4.842 6.949 0 5.5 0A3.5 3.5 0 0 0 2 3.5c.003.52.123 1.033.351 1.5H2a2 2 0 0 0-2 2v3a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1V7a2 2 0 0 0-2-2Z"/></svg></x-slot:icon>
            </x-menu-item>
            @hasanyrole('super-admin')
            <x-menu-item route="settings.index" label="Pengaturan Umum" color-from="gray-500" color-to="slate-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg></x-slot:icon>
            </x-menu-item>
            <x-menu-item route="audit-logs.index" label="Log Audit" color-from="slate-500" color-to="gray-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></x-slot:icon>
            </x-menu-item>
            <x-menu-item route="backup.index" label="Backup & Pulihkan" color-from="green-500" color-to="emerald-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg></x-slot:icon>
            </x-menu-item>
            <x-menu-item route="api-management.index" label="Manajemen API" color-from="indigo-500" color-to="blue-600">
               <x-slot:icon><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg></x-slot:icon>
            </x-menu-item>
            @endhasanyrole
         </div>
         @endhasanyrole

         {{-- Logout --}}
         <li class="pt-6 mt-4 border-t border-gray-700/50">
            <form method="POST" action="{{ route('logout') }}">
               @csrf
               <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center p-3 rounded-xl transition-all duration-200 group text-gray-400 hover:bg-red-500/10 hover:text-red-400">
                  <div class="p-2 rounded-lg bg-gradient-to-br from-red-500 to-rose-600 opacity-60 group-hover:opacity-100">
                     <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                  </div>
                  <span class="flex-1 ms-3 font-semibold">Keluar</span>
               </a>
            </form>
         </li>
      </ul>
   </div>
</aside>
