@php
    // Fetch actual customer names from the database
    $dbCustomers = \App\Models\Customer::whereNotNull('name')
        ->where('name', '!=', '')
        ->pluck('name')
        ->toArray();

    $indianFallbackNames = [
        'Aarav Sharma', 'Priya Patel', 'Rohan Verma', 'Ananya Iyer', 
        'Vikram Malhotra', 'Neha Gupta', 'Aditya Rao', 'Pooja Deshmukh', 
        'Rahul Nair', 'Sneha Joshi', 'Amit Banerjee', 'Riya Kapoor', 
        'Siddharth Mehta', 'Kavita Reddy', 'Rajesh Kumar', 'Deepak Verma', 
        'Meera Nambiar', 'Karan Singhania', 'Tanvi Chawla', 'Sanjay Hegde',
        'Ishaan Roy', 'Divya Sundaram', 'Manish Agrawal', 'Shreya Mukherjee'
    ];

    $allBuyerNames = array_values(array_unique(array_merge($dbCustomers, $indianFallbackNames)));

    $indianCities = [
        'Mumbai', 'Bengaluru', 'Delhi NCR', 'Hyderabad', 'Pune', 'Chennai', 
        'Kolkata', 'Ahmedabad', 'Jaipur', 'Chandigarh', 'Kochi', 'Indore', 
        'Lucknow', 'Gurugram', 'Noida', 'Surat', 'Nagpur', 'Bhopal'
    ];

    $proofBooks = \App\Models\Book::where('status', 'active')
        ->select(['id', 'title', 'slug', 'selling_price', 'price', 'cover_image', 'author_name'])
        ->take(15)
        ->get()
        ->map(fn($b) => [
            'id' => $b->id,
            'title' => $b->title,
            'slug' => $b->slug,
            'author' => $b->author_name,
            'price' => '₹' . number_format((float) $b->selling_price, 0),
            'image' => $b->cover_image ? $b->cover_image_url : asset('images/books/algorithms.jpg'),
            'url' => route('books.show', $b->slug),
        ])
        ->toArray();

    if (empty($proofBooks)) {
        $proofBooks = [
            [
                'id' => 1,
                'title' => 'Algorithms & Elegance',
                'slug' => 'algorithms-elegance',
                'author' => 'Dr. Jane Smith',
                'price' => '₹499',
                'image' => asset('images/books/algorithms.jpg'),
                'url' => route('books.index'),
            ]
        ];
    }
@endphp

<!-- ==========================================
     GLOBAL RECENT SALES NOTIFICATION TOAST (TOP-RIGHT)
     ========================================== -->
<div 
    id="global-sales-toast" 
    class="fixed top-24 right-3 sm:right-6 z-50 transition-all duration-500 ease-out transform translate-x-full opacity-0 pointer-events-none max-w-[320px] sm:max-w-[360px]"
    role="status"
    aria-live="polite"
    onmouseenter="pauseSalesToast()"
    onmouseleave="resumeSalesToast()"
>
    <div class="bg-white/95 backdrop-blur-md rounded-2xl border border-brand-200/90 shadow-[0_16px_40px_-10px_rgba(122,88,169,0.28)] p-3.5 flex items-center gap-3 relative ring-1 ring-black/5 hover:border-brand-400 transition-colors">
        
        <!-- Book Cover Thumbnail (10:7 Rectangular) -->
        <a id="sales-toast-link" href="#" class="block w-16 aspect-[10/7] rounded-xl overflow-hidden bg-slate-950 shrink-0 border border-slate-200 shadow-sm relative group/img">
            <img id="sales-toast-image" src="{{ asset('images/books/algorithms.jpg') }}" alt="Book Cover" class="w-full h-full object-cover">
            <div class="absolute inset-y-0 left-0 w-1 bg-gradient-to-r from-black/40 to-transparent pointer-events-none"></div>
        </a>

        <!-- Content Details -->
        <div class="min-w-0 flex-1 pr-4">
            <div class="flex items-center gap-1.5 leading-none">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <p class="text-xs font-bold text-slate-900 truncate">
                    <span id="sales-toast-name">Aarav Sharma</span>
                </p>
            </div>
            
            <p class="text-[11px] text-slate-500 truncate mt-1">
                <span id="sales-toast-city">from Mumbai</span> • <span class="text-brand-600 font-semibold">purchased</span>
            </p>

            <a id="sales-toast-title-link" href="#" class="block font-semibold text-xs text-slate-800 hover:text-brand-600 transition truncate mt-0.5">
                <span id="sales-toast-title">Algorithms &amp; Elegance</span>
            </a>

            <div class="flex items-center justify-between gap-2 mt-1.5 text-[10px] text-slate-400 font-medium">
                <span class="font-brand text-xs text-brand-600 font-bold" id="sales-toast-price">₹499</span>
                <span class="flex items-center gap-1" id="sales-toast-time">
                    <i class="fa-regular fa-clock text-[9px] text-brand-400"></i>
                    <span>2 mins ago</span>
                </span>
            </div>
        </div>

        <!-- Dismiss Close Button -->
        <button 
            type="button" 
            onclick="dismissSalesToast()" 
            class="absolute top-2 right-2 text-slate-300 hover:text-slate-600 transition p-1 cursor-pointer"
            aria-label="Close notification"
        >
            <i class="fa-solid fa-xmark text-xs"></i>
        </button>
    </div>
</div>

<script>
(function initGlobalSalesToast() {
    const toast = document.getElementById('global-sales-toast');
    const nameEl = document.getElementById('sales-toast-name');
    const cityEl = document.getElementById('sales-toast-city');
    const titleEl = document.getElementById('sales-toast-title');
    const priceEl = document.getElementById('sales-toast-price');
    const timeEl = document.getElementById('sales-toast-time');
    const imgEl = document.getElementById('sales-toast-image');
    const linkEl = document.getElementById('sales-toast-link');
    const titleLinkEl = document.getElementById('sales-toast-title-link');

    if (!toast || !nameEl || !titleEl) return;

    const buyerNames = @json($allBuyerNames);
    const cities = @json($indianCities);
    const books = @json($proofBooks);

    const relativeTimes = [
        'Just now',
        '1 min ago',
        '2 mins ago',
        '4 mins ago',
        '6 mins ago',
        '9 mins ago',
        '12 mins ago',
        '18 mins ago',
        '25 mins ago'
    ];

    let timerId = null;
    let autoHideTimer = null;
    let isPaused = false;
    let lastBookIdx = -1;
    let lastNameIdx = -1;

    function getRandomItem(arr, lastIdx) {
        if (!arr || arr.length === 0) return null;
        if (arr.length === 1) return arr[0];
        let idx = Math.floor(Math.random() * arr.length);
        if (idx === lastIdx) {
            idx = (idx + 1) % arr.length;
        }
        return { item: arr[idx], index: idx };
    }

    function showNextToast() {
        if (isPaused) return;

        const buyerRes = getRandomItem(buyerNames, lastNameIdx);
        const bookRes = getRandomItem(books, lastBookIdx);
        const city = cities[Math.floor(Math.random() * cities.length)];
        const timeAgo = relativeTimes[Math.floor(Math.random() * relativeTimes.length)];

        if (buyerRes) {
            lastNameIdx = buyerRes.index;
            nameEl.textContent = buyerRes.item;
        }
        cityEl.textContent = `from ${city}`;
        timeEl.innerHTML = `<i class="fa-regular fa-clock text-[9px] text-brand-400"></i> <span>${timeAgo}</span>`;

        if (bookRes && bookRes.item) {
            lastBookIdx = bookRes.index;
            const b = bookRes.item;
            titleEl.textContent = b.title || 'Featured E-Book';
            priceEl.textContent = b.price || '₹499';
            if (b.image && imgEl) imgEl.src = b.image;
            if (b.url) {
                if (linkEl) linkEl.href = b.url;
                if (titleLinkEl) titleLinkEl.href = b.url;
            }
        }

        // Animate In from Top-Right
        toast.classList.remove('translate-x-full', 'opacity-0', 'pointer-events-none');
        toast.classList.add('translate-x-0', 'opacity-100', 'pointer-events-auto');

        // Visible for 4.5 seconds
        if (autoHideTimer) clearTimeout(autoHideTimer);
        autoHideTimer = setTimeout(() => {
            hideToast();
            scheduleNext();
        }, 4500);
    }

    function hideToast() {
        toast.classList.remove('translate-x-0', 'opacity-100', 'pointer-events-auto');
        toast.classList.add('translate-x-full', 'opacity-0', 'pointer-events-none');
    }

    function scheduleNext() {
        if (timerId) clearTimeout(timerId);
        // Next toast in 6 to 12 seconds randomly
        const nextInterval = Math.floor(Math.random() * 6000) + 6000;
        timerId = setTimeout(showNextToast, nextInterval);
    }

    window.dismissSalesToast = function() {
        if (autoHideTimer) clearTimeout(autoHideTimer);
        hideToast();
        scheduleNext();
    };

    window.pauseSalesToast = function() {
        isPaused = true;
        if (autoHideTimer) clearTimeout(autoHideTimer);
    };

    window.resumeSalesToast = function() {
        isPaused = false;
        if (autoHideTimer) clearTimeout(autoHideTimer);
        autoHideTimer = setTimeout(() => {
            hideToast();
            scheduleNext();
        }, 2500);
    };

    // First toast after 3 seconds on page load
    setTimeout(showNextToast, 3000);
})();
</script>
