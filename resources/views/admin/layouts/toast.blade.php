<!-- Universal Bottom-Right Toast Container -->
<div id="toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2.5 max-w-md w-[calc(100%-2.5rem)] pointer-events-none sm:w-96" aria-live="polite">
    <!-- Server-rendered Session Toasts -->
    @if (session('success'))
        <div class="toast-item pointer-events-auto flex items-start gap-3 p-4 rounded-2xl bg-white border border-emerald-200/90 text-slate-800 shadow-xl shadow-emerald-950/5 ring-1 ring-emerald-500/10 transition-all duration-300 transform translate-y-0 opacity-100" data-autodismiss="4000">
            <span class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 shadow-2xs">
                <i class="fa-solid fa-circle-check text-sm"></i>
            </span>
            <div class="flex-1 min-w-0 pt-0.5">
                <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-800">Success</h4>
                <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">{{ session('success') }}</p>
            </div>
            <button type="button" onclick="dismissToast(this.closest('.toast-item'))" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer transition">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>
    @endif

    @if (session('error') || session('fail') || session('failure'))
        <div class="toast-item pointer-events-auto flex items-start gap-3 p-4 rounded-2xl bg-white border border-rose-200/90 text-slate-800 shadow-xl shadow-rose-950/5 ring-1 ring-rose-500/10 transition-all duration-300 transform translate-y-0 opacity-100" data-autodismiss="5000">
            <span class="w-8 h-8 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0 shadow-2xs">
                <i class="fa-solid fa-triangle-exclamation text-sm"></i>
            </span>
            <div class="flex-1 min-w-0 pt-0.5">
                <h4 class="text-xs font-bold uppercase tracking-wider text-rose-800">Error</h4>
                <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">{{ session('error') ?? session('fail') ?? session('failure') }}</p>
            </div>
            <button type="button" onclick="dismissToast(this.closest('.toast-item'))" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer transition">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>
    @endif

    @if (session('warning'))
        <div class="toast-item pointer-events-auto flex items-start gap-3 p-4 rounded-2xl bg-white border border-amber-200/90 text-slate-800 shadow-xl shadow-amber-950/5 ring-1 ring-amber-500/10 transition-all duration-300 transform translate-y-0 opacity-100" data-autodismiss="4500">
            <span class="w-8 h-8 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0 shadow-2xs">
                <i class="fa-solid fa-circle-exclamation text-sm"></i>
            </span>
            <div class="flex-1 min-w-0 pt-0.5">
                <h4 class="text-xs font-bold uppercase tracking-wider text-amber-800">Warning</h4>
                <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">{{ session('warning') }}</p>
            </div>
            <button type="button" onclick="dismissToast(this.closest('.toast-item'))" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer transition">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>
    @endif

    @if (session('info') || session('status'))
        <div class="toast-item pointer-events-auto flex items-start gap-3 p-4 rounded-2xl bg-white border border-sky-200/90 text-slate-800 shadow-xl shadow-sky-950/5 ring-1 ring-sky-500/10 transition-all duration-300 transform translate-y-0 opacity-100" data-autodismiss="4000">
            <span class="w-8 h-8 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center shrink-0 shadow-2xs">
                <i class="fa-solid fa-circle-info text-sm"></i>
            </span>
            <div class="flex-1 min-w-0 pt-0.5">
                <h4 class="text-xs font-bold uppercase tracking-wider text-sky-800">Notice</h4>
                <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">{{ session('info') ?? session('status') }}</p>
            </div>
            <button type="button" onclick="dismissToast(this.closest('.toast-item'))" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer transition">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>
    @endif
</div>

<script>
    // Universal Toast Manager
    function dismissToast(toastEl) {
        if (!toastEl) return;
        toastEl.classList.add('opacity-0', 'translate-x-6');
        setTimeout(() => {
            if (toastEl.parentElement) {
                toastEl.remove();
            }
        }, 300);
    }

    function showToast(message, type = 'success', title = null, duration = 4000) {
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'fixed bottom-5 right-5 z-50 flex flex-col gap-2.5 max-w-md w-[calc(100%-2.5rem)] pointer-events-none sm:w-96';
            container.setAttribute('aria-live', 'polite');
            document.body.appendChild(container);
        }

        const config = {
            success: {
                title: title || 'Success',
                icon: 'fa-circle-check',
                iconBg: 'bg-emerald-100 text-emerald-600',
                border: 'border-emerald-200/90 ring-emerald-500/10',
                titleColor: 'text-emerald-800',
            },
            error: {
                title: title || 'Error',
                icon: 'fa-triangle-exclamation',
                iconBg: 'bg-rose-100 text-rose-600',
                border: 'border-rose-200/90 ring-rose-500/10',
                titleColor: 'text-rose-800',
            },
            fail: {
                title: title || 'Error',
                icon: 'fa-triangle-exclamation',
                iconBg: 'bg-rose-100 text-rose-600',
                border: 'border-rose-200/90 ring-rose-500/10',
                titleColor: 'text-rose-800',
            },
            warning: {
                title: title || 'Warning',
                icon: 'fa-circle-exclamation',
                iconBg: 'bg-amber-100 text-amber-600',
                border: 'border-amber-200/90 ring-amber-500/10',
                titleColor: 'text-amber-800',
            },
            info: {
                title: title || 'Notice',
                icon: 'fa-circle-info',
                iconBg: 'bg-sky-100 text-sky-600',
                border: 'border-sky-200/90 ring-sky-500/10',
                titleColor: 'text-sky-800',
            }
        };

        const current = config[type] || config.info;

        const toast = document.createElement('div');
        toast.className = `toast-item pointer-events-auto flex items-start gap-3 p-4 rounded-2xl bg-white border ${current.border} text-slate-800 shadow-xl ring-1 transition-all duration-300 transform translate-y-2 opacity-0`;
        toast.innerHTML = `
            <span class="w-8 h-8 rounded-xl ${current.iconBg} flex items-center justify-center shrink-0 shadow-2xs">
                <i class="fa-solid ${current.icon} text-sm"></i>
            </span>
            <div class="flex-1 min-w-0 pt-0.5">
                <h4 class="text-xs font-bold uppercase tracking-wider ${current.titleColor}">${current.title}</h4>
                <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">${message}</p>
            </div>
            <button type="button" onclick="dismissToast(this.closest('.toast-item'))" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer transition">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        `;

        container.appendChild(toast);

        // Animate in
        requestAnimationFrame(() => {
            toast.classList.remove('translate-y-2', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');
        });

        // Auto dismiss
        if (duration > 0) {
            setTimeout(() => {
                dismissToast(toast);
            }, duration);
        }
    }

    // Auto-dismiss initial server-rendered toasts
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.toast-item[data-autodismiss]').forEach(toast => {
            const time = parseInt(toast.getAttribute('data-autodismiss'), 10) || 4000;
            setTimeout(() => {
                dismissToast(toast);
            }, time);
        });
    });

    window.showToast = showToast;
    window.dismissToast = dismissToast;
</script>
