<!-- ==========================================
     GLOBAL WISHLIST SIDE DRAWER & MANAGER
     ========================================== -->
<div id="wishlist-drawer-wrapper" class="relative z-[60] hidden" aria-labelledby="wishlist-title" role="dialog" aria-modal="true">
    
    <!-- Backdrop Overlay -->
    <div 
        id="wishlist-backdrop" 
        onclick="closeWishlistDrawer()"
        class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs transition-opacity duration-300 opacity-0"
    ></div>

    <!-- Slide-in Drawer Container -->
    <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
        <div 
            id="wishlist-panel" 
            class="w-screen max-w-md bg-white shadow-2xl flex flex-col transform transition-transform duration-300 ease-in-out translate-x-full"
        >
            
            <!-- Drawer Header -->
            <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-500 border border-rose-100 flex items-center justify-center shadow-2xs">
                        <i class="fa-solid fa-heart text-base"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 id="wishlist-title" class="font-brand text-2xl text-slate-900 tracking-wide uppercase leading-none">
                                My Wishlist
                            </h2>
                            <span id="wishlist-drawer-count" class="px-2 py-0.5 rounded-full bg-rose-100 text-rose-700 text-[11px] font-bold">
                                0
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-0.5 font-medium">Your saved favorite e-books</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button 
                        id="wishlist-clear-btn"
                        type="button" 
                        onclick="clearWishlist()" 
                        class="hidden text-[11px] font-semibold text-slate-400 hover:text-rose-600 px-2 py-1 rounded-lg hover:bg-rose-50 transition cursor-pointer"
                        title="Clear all saved books"
                    >
                        Clear All
                    </button>
                    <button 
                        type="button" 
                        onclick="closeWishlistDrawer()" 
                        class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition cursor-pointer"
                        aria-label="Close Wishlist"
                    >
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>
            </div>

            <!-- Drawer Body -->
            <div class="flex-1 overflow-y-auto p-5 sm:p-6 space-y-4">
                
                <!-- Wishlist Empty State -->
                <div id="wishlist-empty-state" class="hidden flex flex-col items-center justify-center text-center py-16 px-4 space-y-4">
                    <div class="w-16 h-16 rounded-full bg-rose-50 text-rose-400 flex items-center justify-center text-2xl border border-rose-100 shadow-inner">
                        <i class="fa-regular fa-heart"></i>
                    </div>
                    <div class="space-y-1">
                        <h3 class="font-brand text-2xl text-slate-900 uppercase tracking-wide">Your Wishlist is Empty</h3>
                        <p class="text-xs text-slate-500 max-w-xs mx-auto leading-relaxed">
                            Explore our library and tap the heart icon on any e-book cover to save it here for later.
                        </p>
                    </div>
                    <a 
                        href="{{ route('books.index') }}" 
                        onclick="closeWishlistDrawer()"
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-brand-600 hover:bg-brand-500 text-white font-brand text-lg uppercase tracking-wider transition shadow-md shadow-brand-600/20 transform hover:-translate-y-0.5 cursor-pointer"
                    >
                        <span>Explore E-Books</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>

                <!-- Wishlist Items List -->
                <div id="wishlist-items-list" class="space-y-3 divide-y divide-slate-100">
                    <!-- Dynamic wishlist cards will be rendered here -->
                </div>

            </div>

            <!-- Drawer Footer -->
            <div id="wishlist-drawer-footer" class="hidden p-5 border-t border-slate-100 bg-slate-50/80 space-y-3">
                <div class="flex items-center justify-between text-xs text-slate-500 font-medium">
                    <span>Total Saved Items</span>
                    <span id="wishlist-footer-count" class="font-bold text-slate-800">0 Books</span>
                </div>
                <a 
                    href="{{ route('books.index') }}" 
                    onclick="closeWishlistDrawer()"
                    class="w-full h-11 inline-flex items-center justify-center px-6 rounded-full bg-brand-600 hover:bg-brand-500 text-white font-brand text-xl tracking-wider uppercase transition shadow-md shadow-brand-600/20 text-center"
                >
                    <span>Continue Browsing</span>
                </a>
            </div>

        </div>
    </div>
</div>

<!-- ==========================================
     GLOBAL WISHLIST TOAST NOTIFICATION
     ========================================== -->
<div 
    id="wishlist-toast" 
    class="fixed bottom-6 right-6 z-[70] transform translate-y-20 opacity-0 pointer-events-none transition-all duration-300 flex items-center gap-3 px-4 py-3 rounded-2xl bg-slate-900 text-white shadow-2xl border border-slate-700/60 max-w-sm"
>
    <div id="wishlist-toast-icon" class="w-8 h-8 rounded-full bg-rose-500/20 text-rose-400 flex items-center justify-center text-sm shrink-0">
        <i class="fa-solid fa-heart"></i>
    </div>
    <div class="flex-1 min-w-0">
        <p id="wishlist-toast-title" class="text-xs font-bold text-white truncate">Book Title</p>
        <p id="wishlist-toast-msg" class="text-[11px] text-slate-300">Added to your wishlist</p>
    </div>
</div>

<!-- ==========================================
     GLOBAL WISHLIST JAVASCRIPT ENGINE
     ========================================== -->
<script>
(function() {
    const STORAGE_KEY = 'ebook_store_wishlist_v1';

    // Get current wishlist items from localStorage
    function getWishlist() {
        try {
            const raw = localStorage.getItem(STORAGE_KEY);
            return raw ? JSON.parse(raw) : [];
        } catch (e) {
            console.error('Error reading wishlist from localStorage', e);
            return [];
        }
    }

    // Save wishlist array to localStorage
    function saveWishlist(items) {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
        } catch (e) {
            console.error('Error saving wishlist to localStorage', e);
        }
        syncWishlistState();
    }

    // Check if a book is in wishlist
    function isBookInWishlist(idOrSlug) {
        if (!idOrSlug) return false;
        const list = getWishlist();
        const key = String(idOrSlug).toLowerCase();
        return list.some(item => 
            (item.id && String(item.id).toLowerCase() === key) || 
            (item.slug && String(item.slug).toLowerCase() === key)
        );
    }

    // Global toggle function
    window.toggleWishlist = function(bookData, event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }

        if (!bookData) return;

        const idOrSlug = bookData.id || bookData.slug || bookData.title;
        let list = getWishlist();
        const existingIndex = list.findIndex(item => 
            (item.id && bookData.id && String(item.id) === String(bookData.id)) ||
            (item.slug && bookData.slug && String(item.slug) === String(bookData.slug)) ||
            (item.title && bookData.title && item.title.toLowerCase() === bookData.title.toLowerCase())
        );

        let isAdded = false;
        if (existingIndex > -1) {
            // Remove
            const removedItem = list.splice(existingIndex, 1)[0];
            saveWishlist(list);
            showWishlistToast(removedItem.title || 'Book', 'Removed from your wishlist', false);
        } else {
            // Add
            const newItem = {
                id: bookData.id || String(Date.now()),
                slug: bookData.slug || '',
                title: bookData.title || 'Untitled E-Book',
                author: bookData.author || 'Author',
                category: bookData.category || 'E-Book',
                price: bookData.price || '₹499',
                original_price: bookData.original_price || '',
                image: bookData.image || '',
                url: bookData.url || (bookData.slug ? `/ebooks/${bookData.slug}` : `/ebooks/${bookData.id || ''}`)
            };
            list.unshift(newItem);
            saveWishlist(list);
            isAdded = true;
            showWishlistToast(newItem.title, 'Saved to your wishlist!', true);
        }

        // Animate clicked button if available
        if (event && event.currentTarget) {
            const btn = event.currentTarget;
            btn.classList.add('scale-125');
            setTimeout(() => btn.classList.remove('scale-125'), 200);
        }
    };

    // Remove single book from wishlist
    window.removeFromWishlist = function(idOrSlug) {
        let list = getWishlist();
        const key = String(idOrSlug).toLowerCase();
        list = list.filter(item => 
            (item.id && String(item.id).toLowerCase() !== key) && 
            (item.slug && String(item.slug).toLowerCase() !== key)
        );
        saveWishlist(list);
    };

    // Clear all books
    window.clearWishlist = function() {
        if (confirm('Are you sure you want to clear your entire wishlist?')) {
            saveWishlist([]);
            showWishlistToast('Wishlist Cleared', 'All saved books were removed', false);
        }
    };

    // Open Wishlist Drawer
    window.openWishlistDrawer = function() {
        const wrapper = document.getElementById('wishlist-drawer-wrapper');
        const backdrop = document.getElementById('wishlist-backdrop');
        const panel = document.getElementById('wishlist-panel');

        if (!wrapper || !backdrop || !panel) return;

        renderWishlistDrawer();
        wrapper.classList.remove('hidden');

        requestAnimationFrame(() => {
            backdrop.classList.remove('opacity-0');
            backdrop.classList.add('opacity-100');
            panel.classList.remove('translate-x-full');
            panel.classList.add('translate-x-0');
        });

        document.body.style.overflow = 'hidden';
    };

    // Close Wishlist Drawer
    window.closeWishlistDrawer = function() {
        const wrapper = document.getElementById('wishlist-drawer-wrapper');
        const backdrop = document.getElementById('wishlist-backdrop');
        const panel = document.getElementById('wishlist-panel');

        if (!wrapper || !backdrop || !panel) return;

        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');
        panel.classList.remove('translate-x-0');
        panel.classList.add('translate-x-full');

        setTimeout(() => {
            wrapper.classList.add('hidden');
            document.body.style.overflow = '';
        }, 300);
    };

    // Render Wishlist Drawer Contents
    function renderWishlistDrawer() {
        const list = getWishlist();
        const emptyState = document.getElementById('wishlist-empty-state');
        const itemsList = document.getElementById('wishlist-items-list');
        const countBadge = document.getElementById('wishlist-drawer-count');
        const clearBtn = document.getElementById('wishlist-clear-btn');
        const footer = document.getElementById('wishlist-drawer-footer');
        const footerCount = document.getElementById('wishlist-footer-count');

        if (countBadge) countBadge.textContent = `${list.length} ${list.length === 1 ? 'item' : 'items'}`;
        if (footerCount) footerCount.textContent = `${list.length} ${list.length === 1 ? 'Book' : 'Books'}`;

        if (list.length === 0) {
            if (emptyState) emptyState.classList.remove('hidden');
            if (itemsList) itemsList.innerHTML = '';
            if (clearBtn) clearBtn.classList.add('hidden');
            if (footer) footer.classList.add('hidden');
            return;
        }

        if (emptyState) emptyState.classList.add('hidden');
        if (clearBtn) clearBtn.classList.remove('hidden');
        if (footer) footer.classList.remove('hidden');

        if (itemsList) {
            itemsList.innerHTML = list.map(item => {
                const bookKey = item.slug || item.id;
                const bookUrl = item.url || `/ebooks/${bookKey}`;
                return `
                    <div class="pt-3.5 first:pt-0 flex items-center gap-3.5 group/item">
                        <!-- Book Cover Thumbnail (10:7) -->
                        <a href="${escapeHtml(bookUrl)}" class="block w-20 aspect-[10/7] rounded-xl overflow-hidden bg-slate-950 shrink-0 shadow-sm border border-slate-200/80 relative">
                            <img 
                                src="${escapeHtml(item.image)}" 
                                alt="${escapeHtml(item.title)}" 
                                class="w-full h-full object-cover group-hover/item:scale-105 transition-transform duration-300"
                                onerror="this.src='/images/books/spotlight.jpg'"
                            >
                            <div class="absolute inset-y-0 left-0 w-1.5 bg-gradient-to-r from-black/35 to-transparent pointer-events-none"></div>
                        </a>

                        <!-- Book Info -->
                        <div class="flex-1 min-w-0">
                            <span class="text-[9px] font-bold text-brand-600 uppercase tracking-wider block truncate">${escapeHtml(item.category || 'E-Book')}</span>
                            <a href="${escapeHtml(bookUrl)}" class="font-normal text-xs sm:text-sm text-slate-800 hover:text-brand-600 transition truncate block leading-snug">
                                ${escapeHtml(item.title)}
                            </a>
                            <p class="text-[11px] text-slate-400 truncate mt-0.5">${escapeHtml(item.author || '')}</p>
                            
                            <div class="flex items-center gap-2 mt-1">
                                <span class="font-brand text-lg text-brand-600 font-bold leading-none">${escapeHtml(item.price || '₹499')}</span>
                                ${item.original_price ? `<span class="font-brand text-xs text-slate-400 line-through leading-none">${escapeHtml(item.original_price)}</span>` : ''}
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col items-end gap-2 shrink-0">
                            <a 
                                href="${escapeHtml(bookUrl)}" 
                                class="px-3 py-1 rounded-full bg-brand-600 hover:bg-brand-500 text-white font-brand text-sm uppercase tracking-wider transition shadow-2xs"
                            >
                                Buy Now
                            </a>
                            <button 
                                type="button" 
                                onclick="removeFromWishlist('${escapeHtml(String(bookKey))}')" 
                                class="text-[11px] text-slate-400 hover:text-rose-600 transition flex items-center gap-1 cursor-pointer"
                                title="Remove from wishlist"
                            >
                                <i class="fa-regular fa-trash-can text-[10px]"></i>
                                <span>Remove</span>
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        }
    }

    // Sync button states on page & navbar badges
    function syncWishlistState() {
        const list = getWishlist();
        const count = list.length;

        // Update Desktop & Mobile Navbar Badges
        const navBadge = document.getElementById('wishlist-nav-badge');
        const mobileBadge = document.getElementById('wishlist-mobile-badge');
        const mobileMenuCount = document.getElementById('wishlist-mobile-menu-count');

        if (navBadge) {
            navBadge.textContent = count;
            if (count > 0) {
                navBadge.classList.remove('hidden');
                navBadge.classList.add('flex');
            } else {
                navBadge.classList.add('hidden');
                navBadge.classList.remove('flex');
            }
        }

        if (mobileBadge) {
            mobileBadge.textContent = count;
            if (count > 0) {
                mobileBadge.classList.remove('hidden');
                mobileBadge.classList.add('flex');
            } else {
                mobileBadge.classList.add('hidden');
                mobileBadge.classList.remove('flex');
            }
        }

        if (mobileMenuCount) {
            mobileMenuCount.textContent = count;
        }

        // Update all book cards on page
        const buttons = document.querySelectorAll('[data-wishlist-key]');
        buttons.forEach(btn => {
            const key = btn.getAttribute('data-wishlist-key');
            const icon = btn.querySelector('i');
            const inWishlist = isBookInWishlist(key);

            if (inWishlist) {
                btn.classList.remove('bg-rose-500', 'text-white', 'border-rose-400', 'border-white/20');
                btn.classList.add('bg-slate-950/70', 'text-rose-500', 'border-rose-500/40');
                if (icon) {
                    icon.className = 'fa-solid fa-heart text-xs text-rose-500';
                }
                btn.setAttribute('title', 'Remove from Wishlist');
            } else {
                btn.classList.remove('bg-rose-500', 'text-rose-500', 'border-rose-500/40');
                btn.classList.add('bg-slate-950/60', 'text-white/90', 'border-white/20');
                if (icon) {
                    icon.className = 'fa-regular fa-heart text-xs';
                }
                btn.setAttribute('title', 'Add to Wishlist');
            }
        });

        // If drawer is currently open, re-render its list
        const wrapper = document.getElementById('wishlist-drawer-wrapper');
        if (wrapper && !wrapper.classList.contains('hidden')) {
            renderWishlistDrawer();
        }
    }

    // Helper: escape HTML
    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // Helper: show floating toast notification
    let toastTimeout = null;
    function showWishlistToast(title, message, isAdded) {
        const toast = document.getElementById('wishlist-toast');
        const toastTitle = document.getElementById('wishlist-toast-title');
        const toastMsg = document.getElementById('wishlist-toast-msg');
        const toastIcon = document.getElementById('wishlist-toast-icon');

        if (!toast || !toastTitle || !toastMsg) return;

        toastTitle.textContent = title;
        toastMsg.textContent = message;

        if (toastIcon) {
            if (isAdded) {
                toastIcon.className = 'w-8 h-8 rounded-full bg-rose-500/20 text-rose-400 flex items-center justify-center text-sm shrink-0';
                toastIcon.innerHTML = '<i class="fa-solid fa-heart"></i>';
            } else {
                toastIcon.className = 'w-8 h-8 rounded-full bg-slate-700 text-slate-300 flex items-center justify-center text-sm shrink-0';
                toastIcon.innerHTML = '<i class="fa-regular fa-heart"></i>';
            }
        }

        toast.classList.remove('translate-y-20', 'opacity-0', 'pointer-events-none');
        toast.classList.add('translate-y-0', 'opacity-100');

        if (toastTimeout) clearTimeout(toastTimeout);
        toastTimeout = setTimeout(() => {
            toast.classList.remove('translate-y-0', 'opacity-100');
            toast.classList.add('translate-y-20', 'opacity-0', 'pointer-events-none');
        }, 2800);
    }

    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', () => {
        syncWishlistState();
    });

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeWishlistDrawer();
        }
    });

})();
</script>
