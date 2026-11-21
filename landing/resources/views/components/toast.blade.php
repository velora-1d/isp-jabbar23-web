{{-- Toast Notification Component --}}
{{-- Usage: Automatically displays Laravel session flash messages --}}

<div x-data="toastNotification()" x-init="init()" @toast.window="addToast($event.detail)"
    class="fixed top-20 right-4 z-50 flex flex-col gap-3 w-full max-w-sm pointer-events-none">

    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.visible" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 translate-x-8"
            class="pointer-events-auto w-full rounded-xl shadow-2xl overflow-hidden" :class="{
                 'bg-gradient-to-r from-emerald-500/90 to-teal-500/90 backdrop-blur-sm': toast.type === 'success',
                 'bg-gradient-to-r from-red-500/90 to-rose-500/90 backdrop-blur-sm': toast.type === 'error',
                 'bg-gradient-to-r from-amber-500/90 to-orange-500/90 backdrop-blur-sm': toast.type === 'warning',
                 'bg-gradient-to-r from-blue-500/90 to-cyan-500/90 backdrop-blur-sm': toast.type === 'info'
             }">

            <div class="p-4 flex items-start gap-3">
                {{-- Icon --}}
                <div class="flex-shrink-0">
                    {{-- Success Icon --}}
                    <template x-if="toast.type === 'success'">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </template>

                    {{-- Error Icon --}}
                    <template x-if="toast.type === 'error'">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </template>

                    {{-- Warning Icon --}}
                    <template x-if="toast.type === 'warning'">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </template>

                    {{-- Info Icon --}}
                    <template x-if="toast.type === 'info'">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </template>
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white" x-text="toast.title"></p>
                    <p class="text-sm text-white/90 mt-0.5" x-text="toast.message" x-show="toast.message"></p>
                </div>

                {{-- Close Button --}}
                <button @click="removeToast(toast.id)"
                    class="flex-shrink-0 p-1 rounded-lg hover:bg-white/20 transition-colors">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            {{-- Progress Bar --}}
            <div class="h-1 bg-white/30">
                <div class="h-full bg-white/60 transition-all duration-100" :style="'width: ' + toast.progress + '%'">
                </div>
            </div>
        </div>
    </template>
</div>

<script>
    function toastNotification() {
        return {
            toasts: [],
            toastId: 0,

            init() {
                // Initialize with Laravel session flash messages
                @if(session('success'))
                    this.addToast({
                        type: 'success',
                        title: 'Berhasil!',
                        message: "{{ session('success') }}"
                    });
                @endif

                @if(session('error'))
                    this.addToast({
                        type: 'error',
                        title: 'Error!',
                        message: "{{ session('error') }}"
                    });
                @endif

                @if(session('warning'))
                    this.addToast({
                        type: 'warning',
                        title: 'Perhatian!',
                        message: "{{ session('warning') }}"
                    });
                @endif

                @if(session('info'))
                    this.addToast({
                        type: 'info',
                        title: 'Informasi',
                        message: "{{ session('info') }}"
                    });
                @endif

                @if(session('status'))
                    this.addToast({
                        type: 'success',
                        title: 'Status',
                        message: "{{ session('status') }}"
                    });
                @endif
        },

            addToast(data) {
                const id = ++this.toastId;
                const toast = {
                    id: id,
                    type: data.type || 'info',
                    title: data.title || 'Notification',
                    message: data.message || '',
                    visible: true,
                    progress: 100,
                    duration: data.duration || 5000
                };

                this.toasts.push(toast);

                // Auto dismiss with progress
                const interval = 50;
                const step = (interval / toast.duration) * 100;

                const progressInterval = setInterval(() => {
                    const toastIndex = this.toasts.findIndex(t => t.id === id);
                    if (toastIndex === -1) {
                        clearInterval(progressInterval);
                        return;
                    }

                    this.toasts[toastIndex].progress -= step;

                    if (this.toasts[toastIndex].progress <= 0) {
                        clearInterval(progressInterval);
                        this.removeToast(id);
                    }
                }, interval);
            },

            removeToast(id) {
                const index = this.toasts.findIndex(t => t.id === id);
                if (index !== -1) {
                    this.toasts[index].visible = false;
                    setTimeout(() => {
                        this.toasts = this.toasts.filter(t => t.id !== id);
                    }, 300);
                }
            }
        }
    }
</script>
