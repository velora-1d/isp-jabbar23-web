<!-- Page Loading Bar (NProgress style) -->
<div x-data="{ loading: false, progress: 0 }" x-on:page-loading-start.window="loading = true; progress = 30"
    x-on:page-loading-progress.window="progress = $event.detail"
    x-on:page-loading-end.window="progress = 100; setTimeout(() => { loading = false; progress = 0 }, 200)" x-init="
        // Auto-detect page navigation
        document.addEventListener('turbo:before-fetch-request', () => $dispatch('page-loading-start'));
        document.addEventListener('turbo:before-fetch-response', () => $dispatch('page-loading-progress', 70));
        document.addEventListener('turbo:load', () => $dispatch('page-loading-end'));

        // For regular form submissions
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', () => $dispatch('page-loading-start'));
        });

        // For regular link clicks
        document.querySelectorAll('a[href]:not([target=_blank])').forEach(link => {
            link.addEventListener('click', (e) => {
                if (!e.ctrlKey && !e.metaKey && !e.shiftKey) {
                    $dispatch('page-loading-start');
                }
            });
        });
    ">
    <!-- Loading Bar -->
    <div x-show="loading" x-transition:enter="transition-opacity duration-150" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed top-0 left-0 right-0 z-[9999] h-1">
        <!-- Background -->
        <div class="absolute inset-0 bg-gray-800/50"></div>

        <!-- Progress Bar -->
        <div class="absolute inset-y-0 left-0 bg-gradient-to-r from-cyan-500 via-blue-500 to-teal-500 transition-all duration-300 ease-out"
            :style="{ width: progress + '%' }">
            <!-- Glow effect -->
            <div class="absolute right-0 top-0 h-full w-20 bg-gradient-to-r from-transparent to-white/30 blur-sm"></div>
        </div>

        <!-- Spinner (right corner) -->
        <div class="absolute right-4 top-3">
            <div class="w-5 h-5 border-2 border-cyan-500 border-t-transparent rounded-full animate-spin"></div>
        </div>
    </div>
</div>
