<footer class="mt-auto border-t border-slate-200/80 bg-white/60 py-4 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500">
        <div>
            &copy; {{ date('Y') }} <span class="font-semibold text-slate-700">{{ config('app.name', 'E-Book Platform') }}</span>. All rights reserved.
        </div>
        <div class="flex items-center gap-4 text-[11px]">
            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-slate-100 font-mono text-slate-600">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                <span>Laravel v13 &bull; PHP 8.3</span>
            </span>
            <a href="#" class="hover:text-brand-600 transition">Documentation</a>
            <a href="#" class="hover:text-brand-600 transition">Support</a>
        </div>
    </div>
</footer>
