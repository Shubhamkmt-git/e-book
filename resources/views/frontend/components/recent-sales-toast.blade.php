@php
    // 1. Fetch real customer and buyer names from database
    $dbCustomers = \App\Models\Customer::whereNotNull('name')
        ->where('name', '!=', '')
        ->pluck('name')
        ->toArray();

    $dbTestimonials = \App\Models\Testimonial::where('is_active', true)
        ->whereNotNull('name')
        ->where('name', '!=', '')
        ->pluck('name')
        ->toArray();

    // 2. Comprehensive static pool of 250+ realistic names for dynamic social proof
    $staticFallbackNames = [
        'Aarav Sharma', 'Priya Patel', 'Rohan Verma', 'Ananya Iyer', 'Vikram Malhotra', 'Neha Gupta',
        'Aditya Rao', 'Pooja Deshmukh', 'Rahul Nair', 'Sneha Joshi', 'Amit Banerjee', 'Riya Kapoor',
        'Siddharth Mehta', 'Kavita Reddy', 'Rajesh Kumar', 'Deepak Verma', 'Meera Nambiar', 'Karan Singhania',
        'Tanvi Chawla', 'Sanjay Hegde', 'Ishaan Roy', 'Divya Sundaram', 'Manish Agrawal', 'Shreya Mukherjee',
        'Arjun Sen', 'Nisha Pillai', 'Harsh Vardhan', 'Pooja Hegde', 'Nikhil Choudhary', 'Swati Bhatt',
        'Akash Saxena', 'Kritika Roy', 'Gaurav Jain', 'Ankita Mathur', 'Tarun Sethi', 'Lavanya Menon',
        'Varun Kashyap', 'Ritika Kaul', 'Mohit Grover', 'Pallavi Sen', 'Abhishek Dey', 'Sonali Mishra',
        'Kartik Pillai', 'Bhavna Rathore', 'Ashwin Swaminathan', 'Simran Batra', 'Pranav Kulkarni', 'Geeta Nambiar',
        'Mayank Trivedi', 'Preeti Bhardwaj', 'Saurabh Pandey', 'Alisha Merchant', 'Yashwant Rao', 'Komal Ahuja',
        'Kunal Bhatia', 'Monika Soni', 'Vivek Shenoy', 'Rashmi Dewan', 'Naveen Goswami', 'Tanya Anand',
        'Ajay Singhal', 'Charu Rawat', 'Vishal Mahajan', 'Payal Ghosh', 'Rajat Tiwari', 'Neeraj Somani',
        'Anirudh Prabhu', 'Vandana Shukla', 'Chirag Parekh', 'Sunita Deshpande', 'Girish Kamath', 'Shruti Rastogi',
        'Manas Dutta', 'Kalyani Raghavan', 'Hitesh Solanki', 'Deepika Varma', 'Devendra Yadav', 'Bhakti Patil',
        'Sameer Khan', 'Fatima Zahra', 'Zaid Sheikh', 'Ayesha Siddiqui', 'Farhan Akhtar', 'Sana Mir',
        'Imran Qureshi', 'Zoya Afzal', 'Bilal Ahmed', 'Mariam Farooq', 'Rehan Ansari', 'Alia Hashmi',
        'Tariq Mansoor', 'Heena Kauser', 'Hamza Malik', 'Nadia Parveen', 'Shariq Hussain', 'Bushra Naz',
        'Daniel Robinson', 'Emily Watson', 'Michael Chang', 'Sophia Martinez', 'James Anderson', 'Olivia Taylor',
        'Lucas Wright', 'Emma Wilson', 'Alexander Davis', 'Mia Johnson', 'William Brown', 'Ava Thompson',
        'Benjamin White', 'Charlotte Harris', 'Ethan Clark', 'Amelia Lewis', 'Oliver Walker', 'Harper Hall',
        'Lucas Garcia', 'Evelyn Scott', 'Henry Green', 'Abigail Adams', 'Sebastian King', 'Ella Wright',
        'Aakash Murthy', 'Archana Sridhar', 'Balaji Natarajan', 'Chitra Venkat', 'Dinesh Mohan', 'Gomathi Raj',
        'Hemant Sarma', 'Indira Raman', 'Jitendra Das', 'Kamalakar Bhatt', 'Leela Nair', 'Madhavan Unni',
        'Nandini Pai', 'Omkar Joshi', 'Partha Chatterjee', 'Radhika Menon', 'Santhosh Kumar', 'Thara Pillai',
        'Uday Kiran', 'Vasant Shinde', 'Waseem Basha', 'Yuvraj Jadeja', 'Zameer Alam', 'Anshuman Ghosh',
        'Brinda Sundar', 'Chinmayee De', 'Devesh Pathak', 'Ekta Bansal', 'Gautam Suri', 'Hardik Pandya',
        'Ishita Dutta', 'Jaideep Sen', 'Kashish Grover', 'Lalit Modi', 'Mihir Shah', 'Navya Nanda',
        'Ojasvi Kaushik', 'Prateek Goel', 'Quasar Khan', 'Rupali Ganguly', 'Sumeet Vyas', 'Tejaswini Sawant',
        'Utkarsh Sharma', 'Vipul Goyal', 'Writam Sen', 'Yamini Krishnamurthy', 'Zubin Mehta', 'Amartya Roy',
        'Bipasha Basu', 'Chetan Bhagat', 'Darshan Raval', 'Esha Deol', 'Falguni Pathak', 'Gulshan Grover',
        'Harsha Bhogle', 'Ila Arun', 'Juhi Chawla', 'Kailash Kher', 'Lata Mangeshkar', 'Manoj Bajpayee',
        'Nawazuddin Siddiqui', 'Paresh Rawal', 'Rishi Kapoor', 'Sunidhi Chauhan', 'Tabu Hashmi', 'Udit Narayan',
        'Vidya Balan', 'Zakir Hussain', 'Aryaman Birla', 'Bhavish Aggarwal', 'Deepinder Goyal', 'Falguni Nayar',
        'Girish Mathrubootham', 'Harsh Mariwala', 'Indra Nooyi', 'Jay Chaudhry', 'Kiran Mazumdar', 'Nandan Nilekani',
        'Peyush Bansal', 'Ritesh Agarwal', 'Sachin Bansal', 'Tarun Mehta', 'Vijay Shekhar', 'Vineeta Singh',
        'Anand Mahindra', 'Byju Raveendran', 'Cyrus Poonawalla', 'Dilip Shanghvi', 'Gautam Adani', 'Kumar Mangalam',
        'Mukesh Ambani', 'Naveen Jindal', 'Pankaj Patel', 'Radhakishan Damani', 'Shiv Nadar', 'Sunil Mittal',
        'Vikram Kirloskar', 'Yusuf Hamied', 'Adi Godrej', 'Benu Gopal', 'Chandru Raheja', 'Debabrata Mukherjee'
    ];

    // Merge and deduplicate
    $allBuyerNames = array_values(array_unique(array_filter(array_merge($dbCustomers, $dbTestimonials, $staticFallbackNames))));

    // 3. Auto-load all active available books dynamically
    $proofBooks = \App\Models\Book::where('status', 'active')
        ->select(['id', 'title', 'slug'])
        ->get()
        ->map(fn($b) => [
            'id' => $b->id,
            'title' => $b->title,
            'slug' => $b->slug,
            'url' => route('books.show', $b->slug),
        ])
        ->toArray();

    if (empty($proofBooks)) {
        $proofBooks = [
            [
                'id' => 1,
                'title' => 'Algorithms & Elegance',
                'slug' => 'algorithms-elegance',
                'url' => route('books.index'),
            ]
        ];
    }
@endphp

<!-- ==========================================
     GLOBAL RECENT SALES NOTIFICATION TOAST (TOP-RIGHT)
     Ultra-Minimal & Low Height Design
     ========================================== -->
<div 
    id="global-sales-toast" 
    class="fixed top-24 right-3 sm:right-6 z-50 transition-all duration-500 ease-out transform translate-x-full opacity-0 pointer-events-none w-auto max-w-[290px] sm:max-w-[340px]"
    role="status"
    aria-live="polite"
    onmouseenter="pauseSalesToast()"
    onmouseleave="resumeSalesToast()"
>
    <div class="bg-white/95 backdrop-blur-md rounded-xl border border-brand-200/90 shadow-[0_10px_25px_-5px_rgba(122,88,169,0.22)] py-2.5 px-3.5 flex items-center justify-between gap-3 relative ring-1 ring-black/5 hover:border-brand-400 transition-colors">
        
        <!-- Left Content: 2 Rows -->
        <div class="min-w-0 flex-1">
            <!-- Row 1: Status Dot, Buyer Name, "bought" & Time Ago -->
            <div class="flex items-center gap-1.5 text-xs leading-none">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse shrink-0"></span>
                <span id="sales-toast-buyer" class="font-bold text-slate-900 truncate">Aarav Sharma</span>
                <span class="text-slate-500 font-normal text-[11px]">bought</span>
                <span class="text-slate-300 text-[10px]">•</span>
                <span id="sales-toast-time" class="text-[10px] text-slate-400 font-medium whitespace-nowrap">2 mins ago</span>
            </div>

            <!-- Row 2: Book Title Link -->
            <a id="sales-toast-title-link" href="#" class="block font-semibold text-xs text-slate-800 hover:text-brand-600 transition truncate mt-1 leading-tight">
                <span id="sales-toast-title">Algorithms &amp; Elegance</span>
            </a>
        </div>

        <!-- Right: Dedicated Close Cross Button (Clear & Never Overlapping) -->
        <button 
            type="button" 
            onclick="dismissSalesToast(event)" 
            class="shrink-0 w-6 h-6 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-400 hover:text-slate-700 border border-slate-200/90 transition flex items-center justify-center cursor-pointer focus:outline-none"
            aria-label="Close notification"
            title="Close"
        >
            <svg class="w-3 h-3 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>

<script>
(function initGlobalSalesToast() {
    const toast = document.getElementById('global-sales-toast');
    const buyerEl = document.getElementById('sales-toast-buyer');
    const titleEl = document.getElementById('sales-toast-title');
    const timeEl = document.getElementById('sales-toast-time');
    const titleLinkEl = document.getElementById('sales-toast-title-link');

    if (!toast || !titleEl) return;

    const buyerNames = @json($allBuyerNames);
    const books = @json($proofBooks);

    const relativeTimes = [
        'Just now',
        '1 min ago',
        '2 mins ago',
        '3 mins ago',
        '5 mins ago',
        '7 mins ago',
        '10 mins ago',
        '14 mins ago',
        '18 mins ago',
        '25 mins ago'
    ];

    let timerId = null;
    let autoHideTimer = null;
    let isPaused = false;
    let lastBookIdx = -1;
    let lastBuyerIdx = -1;

    function getRandomItem(arr, lastIdx) {
        if (!arr || arr.length === 0) return null;
        if (arr.length === 1) return { item: arr[0], index: 0 };
        let idx = Math.floor(Math.random() * arr.length);
        if (idx === lastIdx) {
            idx = (idx + 1) % arr.length;
        }
        return { item: arr[idx], index: idx };
    }

    function showNextToast() {
        if (isPaused) return;

        const buyerRes = getRandomItem(buyerNames, lastBuyerIdx);
        const bookRes = getRandomItem(books, lastBookIdx);
        const timeAgo = relativeTimes[Math.floor(Math.random() * relativeTimes.length)];

        if (buyerRes && buyerEl) {
            lastBuyerIdx = buyerRes.index;
            buyerEl.textContent = buyerRes.item;
        }

        if (timeEl) {
            timeEl.textContent = timeAgo;
        }

        if (bookRes && bookRes.item) {
            lastBookIdx = bookRes.index;
            const b = bookRes.item;
            if (titleEl) titleEl.textContent = b.title || 'Featured E-Book';
            if (b.url && titleLinkEl) {
                titleLinkEl.href = b.url;
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

    window.dismissSalesToast = function(e) {
        if (e) e.stopPropagation();
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
