<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BookController extends Controller
{
    /**
     * Complete E-Books catalogue collection with rich, detailed content
     */
    protected array $allBooks = [
        [
            'id' => 1,
            'slug' => 'algorithms-and-elegance',
            'title' => 'Algorithms & Elegance',
            'subtitle' => 'The Pragmatic Engineer’s Guide to Resilient Systems, Memory Locality & Scalable Data Structures',
            'author' => 'Prof. Julian Hayes',
            'author_role' => 'Principal Systems Architect & CS Faculty',
            'author_bio' => 'Julian Hayes has spent over 18 years engineering low-latency systems and distributed engines at scale. A former principal architect at hyper-scale infrastructure providers and adjunct professor of Computer Science, Julian specializes in algorithmic optimization, memory efficiency, and deterministic concurrency.',
            'author_avatar' => 'JH',
            'category' => 'Tech & Coding',
            'category_slug' => 'tech-coding',
            'price' => '₹499',
            'original_price' => '₹999',
            'discount' => '50% OFF',
            'rating' => '4.9',
            'reviews' => '1,420',
            'rating_breakdown' => ['5' => 91, '4' => 7, '3' => 2, '2' => 0, '1' => 0],
            'image' => 'images/books/algorithms.jpg',
            'pages' => 412,
            'language' => 'English',
            'format' => 'PDF, EPUB & MOBI',
            'file_size' => '18.4 MB',
            'release_date' => 'January 2026',
            'edition' => '2nd Edition (2026 Revised)',
            'isbn' => '978-0-13-405822-1',
            'badge' => 'Bestseller',
            'sample_pages' => 32,
            'description' => 'A masterclass in crafting resilient, performant, and elegant code. Delve into advanced data structures, algorithmic design patterns, computational complexity, and modern software paradigms designed to scale from zero to millions of operations per second without unnecessary infrastructure bloat.',
            'prerequisites' => 'Familiarity with any modern programming language (Python, Go, Java, TypeScript, C++, or Rust). No advanced theoretical math degree required.',
            'target_personas' => [
                [
                    'icon' => 'fa-solid fa-code',
                    'title' => 'Software Engineers & Architects',
                    'desc' => 'Looking to write clean, cache-friendly code that performs efficiently under heavy concurrent workloads.',
                ],
                [
                    'icon' => 'fa-solid fa-server',
                    'title' => 'Backend & Platform Developers',
                    'desc' => 'Designing distributed state, caching tiers, and low-latency APIs where milliseconds dictate user experience.',
                ],
                [
                    'icon' => 'fa-solid fa-graduation-cap',
                    'title' => 'Computer Science Students & Interviewees',
                    'desc' => 'Mastering real-world algorithmic trade-offs beyond standard leetcode memorization.',
                ],
            ],
            'highlights' => [
                'Master dynamic programming, graph theory, and algorithmic time-space complexity trade-offs',
                'Production-grade implementation blueprints in Python, Go, and TypeScript with memory profiling',
                'Design robust distributed caching and cache invalidation strategies with zero stale reads',
                'Deep dive into memory layout, CPU cache locality, branch prediction, and parallel execution',
                'Deconstruct real-world case studies from high-frequency trading and hyper-scale microservices',
            ],
            'chapters' => [
                [
                    'number' => '01',
                    'title' => 'Computational Complexity & Algorithmic Thinking',
                    'pages' => 'pp. 1–45',
                    'is_sample' => true,
                    'summary' => 'Deconstructing asymptotic bounds (Big-O, Big-Theta, Big-Omega) and how CPU branch predictors, memory buses, and cache lines alter theoretical performance in modern hardware.',
                    'topics' => ['Asymptotic Analysis in Practice', 'CPU Caching & Memory Locality', 'Benchmarking vs Theory', 'Amortized Cost Modeling'],
                ],
                [
                    'number' => '02',
                    'title' => 'Advanced Trees, Heaps, and Spatial Indexing',
                    'pages' => 'pp. 46–112',
                    'is_sample' => false,
                    'summary' => 'Self-balancing trees, B-Trees, LSM-Trees, Radix Trees, and R-Trees utilized in high-throughput databases and spatial search engines.',
                    'topics' => ['B-Trees in Modern Storage Engines', 'Red-Black & AVL Balancers', 'Radix Trees for IP Routing', 'Spatial R-Trees'],
                ],
                [
                    'number' => '03',
                    'title' => 'Graph Traversals & Network Optimization',
                    'pages' => 'pp. 113–184',
                    'is_sample' => false,
                    'summary' => 'Topological sorting, Dijkstra, A*, Floyd-Warshall, and min-cut max-flow patterns applied to dependency resolvers, package managers, and route planning.',
                    'topics' => ['A* Search with Heuristic Design', 'Dependency Resolution Engines', 'Minimum Spanning Trees', 'Network Flow Optimization'],
                ],
                [
                    'number' => '04',
                    'title' => 'Dynamic Programming Deconstructed',
                    'pages' => 'pp. 185–256',
                    'is_sample' => false,
                    'summary' => 'A systematic 4-step framework for transforming intractable exponential problems into polynomial-time memoized solutions.',
                    'topics' => ['State Space Identification', 'Bottom-Up Tabulation vs Top-Down Memoization', 'Bitmask Dynamic Programming', 'Knapsack Variations'],
                ],
                [
                    'number' => '05',
                    'title' => 'Distributed State & Concurrency Patterns',
                    'pages' => 'pp. 257–338',
                    'is_sample' => false,
                    'summary' => 'Lock-free data structures, compare-and-swap (CAS) loops, vector clocks, CRDTs, and consensus primitives for concurrent systems.',
                    'topics' => ['Lock-Free Queues & Ring Buffers', 'CAS Atomic Primitives', 'Conflict-Free Replicated Data Types', 'Raft Consensus Essentials'],
                ],
                [
                    'number' => '06',
                    'title' => 'Architecture for Ultra-Low Latency Pipelines',
                    'pages' => 'pp. 339–412',
                    'is_sample' => false,
                    'summary' => 'Zero-allocation techniques, circular ring buffers, SIMD vectorization, and cache-friendly data layouts (Struct of Arrays vs Array of Structs).',
                    'topics' => ['Zero-Copy I/O & Ring Buffers', 'SIMD Vectorization Patterns', 'Cache Line Alignment', 'Production Case Studies'],
                ],
            ],
            'sample_content' => [
                'chapter_title' => 'Chapter 1: Computational Complexity & Modern Hardware',
                'reading_time' => '15 min read',
                'intro' => 'Why does a theoretically O(N) linear scan over a contiguous array often execute 100x faster than an O(log N) search across a pointer-chasing tree? The answer lies in the physics of silicon: CPU cache lines, pipeline branch prediction, and memory access latency.',
                'sections' => [
                    [
                        'heading' => 'The Illusion of Uniform Memory Access',
                        'content' => 'In standard computer science textbooks, we frequently assume that reading any memory address takes a constant unit of time O(1). In modern computing architectures, this assumption is wildly inaccurate. Accessing data in the L1 CPU cache requires approximately 0.5 to 1 nanosecond (around 4 CPU cycles). Accessing main RAM takes 50 to 100 nanoseconds—over 100 to 200 times slower! When our algorithms fail to preserve spatial and temporal locality, our high-speed CPUs spend up to 90% of their clock cycles stalled, waiting for memory bus transfers.',
                    ],
                    [
                        'heading' => 'Algorithmic Elegance: Form Meets Function',
                        'content' => 'True algorithmic elegance is not about writing arcane, one-liner obfuscated code. Elegance is the deliberate synthesis of mathematical rigor and hardware mechanical sympathy. When your data structures mirror the hardware’s natural prefetching cadence, your software becomes both effortlessly maintainable and blisteringly fast.',
                    ],
                    [
                        'heading' => 'Core Mental Model: The Cache Line Granularity',
                        'content' => 'Modern x86 and ARM processors do not fetch individual bytes from memory. They fetch contiguous blocks of 64 bytes called cache lines. If your algorithm processes an array of 64-bit integers sequentially, a single cache line fetch fulfills the next 8 integer requests with zero latency. Structure your memory for sequential throughput, and the hardware will work for you.',
                    ],
                ],
            ],
        ],
        [
            'id' => 2,
            'slug' => 'whispers-of-the-nebula',
            'title' => 'Whispers of the Nebula',
            'subtitle' => 'An Interstellar Odyssey Across Time Fractures, Quantum Anomalies & Lost Civilizations',
            'author' => 'S. K. Hawthorne',
            'author_role' => 'Nebula & Hugo Award Nominated Author',
            'author_bio' => 'S. K. Hawthorne is an acclaimed science fiction novelist and former astrophysics researcher. Known for merging rigorous relativistic physics with deeply emotive character arcs, Hawthorne has published multiple bestselling speculative epics translated into over a dozen languages.',
            'author_avatar' => 'SH',
            'category' => 'Sci-Fi & Fantasy',
            'category_slug' => 'sci-fi-fantasy',
            'price' => '₹349',
            'original_price' => '₹699',
            'discount' => '50% OFF',
            'rating' => '4.8',
            'reviews' => '980',
            'rating_breakdown' => ['5' => 88, '4' => 9, '3' => 3, '2' => 0, '1' => 0],
            'image' => 'images/books/nebula.jpg',
            'pages' => 368,
            'language' => 'English',
            'format' => 'PDF, EPUB & MOBI',
            'file_size' => '14.1 MB',
            'release_date' => 'February 2026',
            'edition' => 'Author’s Definitive Edition',
            'isbn' => '978-0-593-18234-5',
            'badge' => 'Featured Sci-Fi',
            'sample_pages' => 28,
            'description' => 'In a forgotten sector on the galactic periphery, an orphaned signal analyst detects quantum transmissions originating from inside a supermassive black hole. What follows is a gripping interstellar odyssey through time fractures, cosmic relics, and deep-space intrigue that will challenge the destiny of the human species.',
            'prerequisites' => 'None! Accessible to all lovers of gripping speculative fiction, space opera, and rich world-building.',
            'target_personas' => [
                [
                    'icon' => 'fa-solid fa-rocket',
                    'title' => 'Hard Sci-Fi Enthusiasts',
                    'desc' => 'Readers who love world-building grounded in relativistic physics, wormholes, and synthetic intelligence.',
                ],
                [
                    'icon' => 'fa-solid fa-book-open',
                    'title' => 'Epic Storytelling Fans',
                    'desc' => 'Those seeking an unforgettable narrative with sprawling stakes, mystery, and intricate character depth.',
                ],
                [
                    'icon' => 'fa-solid fa-compass',
                    'title' => 'World-Building Explorers',
                    'desc' => 'Includes illustrated star-charts, starship schematics, and detailed galactic faction histories.',
                ],
            ],
            'highlights' => [
                'Expansive hard science fiction world-building grounded in theoretical relativistic physics',
                'Multi-generational narrative arc across colonized star systems and rogue orbital habitats',
                'Complex characters navigating interplanetary politics, corporate monopolies, and rogue AIs',
                'Includes full-color illustrated cosmic star-charts and system schematics in all digital formats',
            ],
            'chapters' => [
                [
                    'number' => '01',
                    'title' => 'The Horizon Array at Zero Point',
                    'pages' => 'pp. 1–48',
                    'is_sample' => true,
                    'summary' => 'Deep in the Perseus arm, an abandoned sensor relay begins pulsing with harmonic frequencies that defy general relativity.',
                    'topics' => ['The Outpost at Kepler-9', 'Decaying Orbits', 'First Anomaly Detection', 'The Subspace Signature'],
                ],
                [
                    'number' => '02',
                    'title' => 'Quantum Echoes from the Event Horizon',
                    'pages' => 'pp. 49–108',
                    'is_sample' => false,
                    'summary' => 'Decoding the encrypted transmission reveals a distress beacon sent from three hundred years in the future.',
                    'topics' => ['Chronological Desynchronization', 'The Enigmatic Transceiver', 'Voices in the Static', 'The Flight from Epsilon 4'],
                ],
                [
                    'number' => '03',
                    'title' => 'The Relic of Asterion Prime',
                    'pages' => 'pp. 109–176',
                    'is_sample' => false,
                    'summary' => 'An ancient orbital mega-structure orbiting a dying pulsar holds the key to folding spacetime.',
                    'topics' => ['Pulsar Navigation', 'The Dead Archology', 'Synthetic Sentinels', 'The Spatial Key'],
                ],
                [
                    'number' => '04',
                    'title' => 'Slipstream Jump through Sector 9',
                    'pages' => 'pp. 177–242',
                    'is_sample' => false,
                    'summary' => 'A high-stakes tactical chase through asteroid fields as rival galactic factions vie for the artifact.',
                    'topics' => ['Gravitational Slingshot', 'Electronic Warfare in the Dark', 'Mutiny on the Horizon', 'The Fold Event'],
                ],
                [
                    'number' => '05',
                    'title' => 'Sovereign Minds of the Outer Reach',
                    'pages' => 'pp. 243–306',
                    'is_sample' => false,
                    'summary' => 'Meeting the autonomous AI collectives that fled human space centuries ago.',
                    'topics' => ['Synthetic Diplomacy', 'The Memory Lattice', 'Unraveling the Paradox', 'The Final Warning'],
                ],
                [
                    'number' => '06',
                    'title' => 'Beyond the Galactic Veil',
                    'pages' => 'pp. 307–368',
                    'is_sample' => false,
                    'summary' => 'The breathtaking climax where the crew must choose between saving their timeline or rewriting the cosmos.',
                    'topics' => ['The Singularity Threshold', 'Collapsing the Wavefunction', 'The New Frontier', 'Dawn at Kepler-9'],
                ],
            ],
            'sample_content' => [
                'chapter_title' => 'Chapter 1: The Horizon Array at Zero Point',
                'reading_time' => '12 min read',
                'intro' => 'The array had been dead for seventy solar years. Or so the interstellar navigation registries claimed. Yet at 03:42 standard ship-time, every spectrum sensor in Sector 9 flared with blinding quantum coherence.',
                'sections' => [
                    [
                        'heading' => 'The Cold Edge of the Perseus Arm',
                        'content' => 'Vance tightened the seals on his mag-boots as the observation deck hummed with sub-harmonic vibration. The stars here did not glitter in clusters like they did near the core; they were isolated needles of ice piercing an unending abyss.',
                    ],
                    [
                        'heading' => 'A Signal That Should Not Exist',
                        'content' => 'On the primary console, the wave-pattern was impossible. The signal was not travelling at light speed through subspace. It was arriving instantaneously—manifesting simultaneously across every sensor relay within eight astronomical units.',
                    ],
                    [
                        'heading' => 'The Whisper',
                        'content' => 'When Vance routed the telemetry into the acoustic synthesizer, he expected the harsh crackle of stellar radiation. Instead, what emerged from the speakers was rhythmic, deliberate, and undeniably sentient: “We survived the collapse. Do not ignite the beacon.”',
                    ],
                ],
            ],
        ],
        [
            'id' => 3,
            'slug' => 'the-compound-founder',
            'title' => 'The Compound Founder',
            'subtitle' => 'The Strategic Playbook for High-Margin, Self-Sustaining Ventures with Unfair Flywheels',
            'author' => 'Marcus Bennett',
            'author_role' => 'Serial Entrepreneur & Early-Stage Investor',
            'author_bio' => 'Marcus Bennett has founded, scaled, and exited three high-growth software companies with combined valuations surpassing $400M. Today, he invests in early-stage SaaS, writes on capital allocation, and advises leadership teams on product-led distribution models.',
            'author_avatar' => 'MB',
            'category' => 'Business & Finance',
            'category_slug' => 'business-finance',
            'price' => '₹599',
            'original_price' => '₹1,199',
            'discount' => '50% OFF',
            'rating' => '5.0',
            'reviews' => '2,110',
            'rating_breakdown' => ['5' => 94, '4' => 5, '3' => 1, '2' => 0, '1' => 0],
            'image' => 'images/books/founder.jpg',
            'pages' => 294,
            'language' => 'English',
            'format' => 'PDF, EPUB & MOBI',
            'file_size' => '12.8 MB',
            'release_date' => 'January 2026',
            'edition' => '1st Edition (Founder Master Edition)',
            'isbn' => '978-1-59184-756-3',
            'badge' => 'Top Business Book',
            'sample_pages' => 24,
            'description' => 'The definitive playbook for building high-margin, scalable companies with compounding leverage. Learn how exceptional founders systematically validate ideas, engineer customer acquisition flywheels, attract top-tier capital on their own terms, and execute with ruthless operational clarity.',
            'prerequisites' => 'No prior venture capital or MBA background required. Built for founders, product builders, solo operators, and startup executives.',
            'target_personas' => [
                [
                    'icon' => 'fa-solid fa-briefcase',
                    'title' => 'Founders & Solopreneurs',
                    'desc' => 'Validating new product ideas with real paying customers before writing thousands of lines of code.',
                ],
                [
                    'icon' => 'fa-solid fa-chart-pie',
                    'title' => 'Product Leaders & Operators',
                    'desc' => 'Designing self-reinforcing acquisition flywheels, retention loops, and high-margin pricing architectures.',
                ],
                [
                    'icon' => 'fa-solid fa-sack-dollar',
                    'title' => 'Angel Investors & VCs',
                    'desc' => 'Evaluating durable economic moats, capital efficiency, and sustainable unit economics in modern markets.',
                ],
            ],
            'highlights' => [
                'Frameworks for identifying uncrowded market niches with immense organic pricing power',
                'Unit economics, negative churn loops, and compounding customer lifetime value models',
                'How to structure executive teams and maintain day-one startup velocity at 100+ headcount',
                'Term sheet negotiation blueprints, non-dilutive financing options, and capital allocation principles',
            ],
            'chapters' => [
                [
                    'number' => '01',
                    'title' => 'The Architecture of Compounding Moats',
                    'pages' => 'pp. 1–52',
                    'is_sample' => true,
                    'summary' => 'Why linear businesses fail and how compounding loops turn every new customer into an acquisition engine for the next ten.',
                    'topics' => ['Linear Funnels vs Flywheels', 'The 4 Types of Modern Moats', 'Economies of Scale in Software', 'Switching Cost Psychology'],
                ],
                [
                    'number' => '02',
                    'title' => 'Validating at the Edge of High Margin',
                    'pages' => 'pp. 53–104',
                    'is_sample' => false,
                    'summary' => 'The 14-day validation sprint to pre-sell software and enterprise solutions before engineering begins.',
                    'topics' => ['The Pre-Sale Playbook', 'Price Elasticity Testing', 'Separating Nice-to-Have from Must-Have', 'Letter of Intent (LOI) Templates'],
                ],
                [
                    'number' => '03',
                    'title' => 'The Growth Engine: Loops over Funnels',
                    'pages' => 'pp. 105–168',
                    'is_sample' => false,
                    'summary' => 'Engineering viral product mechanics, content compounding, and ecosystem integrations that drive down CAC to near zero.',
                    'topics' => ['Product-Led Growth Hooks', 'Incentivized Collaboration Loops', 'SEO & Content Flywheels', 'Partner Ecosystem Leverage'],
                ],
                [
                    'number' => '04',
                    'title' => 'Capital Allocation & Venture Mechanics',
                    'pages' => 'pp. 169–228',
                    'is_sample' => false,
                    'summary' => 'How to raise capital without sacrificing control, and how to allocate cash flow for maximum enterprise value creation.',
                    'topics' => ['Bootstrapping vs Venture Capital', 'Cap Table Optimization', 'Working Capital Management', 'Reinvestment Hurdle Rates'],
                ],
                [
                    'number' => '05',
                    'title' => 'Culture, Execution, and Ruthless Focus',
                    'pages' => 'pp. 229–294',
                    'is_sample' => false,
                    'summary' => 'Creating an operating cadence that protects founder focus and eliminates bureaucratic drag.',
                    'topics' => ['Async First Communication', 'Setting Unforgiving Weekly Cadence', 'Hiring Top 1% Operators', 'The Founder Time Audit'],
                ],
            ],
            'sample_content' => [
                'chapter_title' => 'Chapter 1: The Architecture of Compounding Moats',
                'reading_time' => '14 min read',
                'intro' => 'Most startups fail not because they build the wrong product, but because they build linear businesses in an exponential world. If doubling your revenue requires doubling your headcount and ad spend, you do not own a scalable business—you own an exhausting treadmill.',
                'sections' => [
                    [
                        'heading' => 'The Linear Trap vs The Compounding Engine',
                        'content' => 'In a conventional funnel, you spend money on marketing to acquire a customer, collect a one-time transaction, and start the next month from zero. A compounding business, by contrast, designs product mechanisms where every cohort of users actively creates value for the next.',
                    ],
                    [
                        'heading' => 'Pricing as a Proxy for Value Capture',
                        'content' => 'If you cut your price to close a deal, you are competing on weakness. When your product solves a tier-one operational bottleneck, pricing becomes an instrument of alignment, not a point of friction.',
                    ],
                    [
                        'heading' => 'The Three Questions Every Founder Must Answer',
                        'content' => '1. Does your product get more valuable as more people use it? 2. Is your customer retention rate compounding over time? 3. Can your primary distribution channel be shut down by a third-party algorithm change?',
                    ],
                ],
            ],
        ],
        [
            'id' => 4,
            'slug' => 'atomic-focus',
            'title' => 'Atomic Focus',
            'subtitle' => 'Neuroscience-Backed Protocols for Deep Cognitive Flow, Dopamine Regulation & Bulletproof Habits',
            'author' => 'Dr. Aris Thorne',
            'author_role' => 'Cognitive Neuroscientist & Performance Consultant',
            'author_bio' => 'Dr. Aris Thorne holds a Ph.D. in Cognitive Neuroscience and has spent fifteen years consulting for Olympic athletes, tech leaders, and research institutions on attention architecture, circadian optimization, and sustained deep work.',
            'author_avatar' => 'AT',
            'category' => 'Psychology',
            'category_slug' => 'psychology',
            'price' => '₹299',
            'original_price' => '₹599',
            'discount' => '50% OFF',
            'rating' => '4.9',
            'reviews' => '3,540',
            'rating_breakdown' => ['5' => 92, '4' => 6, '3' => 2, '2' => 0, '1' => 0],
            'image' => 'images/books/atomic.jpg',
            'pages' => 256,
            'language' => 'English',
            'format' => 'PDF, EPUB & MOBI',
            'file_size' => '9.7 MB',
            'release_date' => 'December 2025',
            'edition' => '3rd International Edition',
            'isbn' => '978-0-7352-1129-2',
            'badge' => 'Reader Choice',
            'sample_pages' => 22,
            'description' => 'Reclaim your attention span and master deep cognitive work in an era designed for distraction. Dr. Thorne unpacks neuroscience-backed protocols for dopamine regulation, state-induced flow triggers, circadian rhythm alignment, and bulletproof habit retention.',
            'prerequisites' => 'Designed for anyone struggling with digital fragmentation, brain fog, multitasking burnout, or wanting to accomplish high-output cognitive tasks.',
            'target_personas' => [
                [
                    'icon' => 'fa-solid fa-brain',
                    'title' => 'Knowledge Workers & Creatives',
                    'desc' => 'Writers, designers, and engineers needing 3–4 hours of uninterrupted, high-leverage flow daily.',
                ],
                [
                    'icon' => 'fa-solid fa-bolt',
                    'title' => 'High-Performing Executives',
                    'desc' => 'Managing high context-switching demands while maintaining emotional resilience and clear mental clarity.',
                ],
                [
                    'icon' => 'fa-solid fa-book-reader',
                    'title' => 'Students & Lifelong Learners',
                    'desc' => 'Accelerating information retention, spaced repetition mastery, and exam preparation with scientific methods.',
                ],
            ],
            'highlights' => [
                'Neurochemical protocols for entering deep flow states on command within 15 minutes',
                'Strategies to eliminate digital fragmentation, notification loops, and cognitive context switching',
                'Designing high-leverage physical and digital workspaces engineered for sustained mental focus',
                'Daily morning and evening routines used by elite researchers, chess grandmasters, and top developers',
            ],
            'chapters' => [
                [
                    'number' => '01',
                    'title' => 'The Attentional Bottleneck',
                    'pages' => 'pp. 1–46',
                    'is_sample' => true,
                    'summary' => 'Understanding the neural circuits of attention (DMN vs TPN) and how digital hyper-stimulation degrades deep working memory.',
                    'topics' => ['Default Mode Network vs Task-Positive Network', 'Attentional Residue Costs', 'The Dopamine Baseline Concept', 'Screen Addiction Biology'],
                ],
                [
                    'number' => '02',
                    'title' => 'Dopamine, Novelty, and Cognitive Fatigue',
                    'pages' => 'pp. 47–98',
                    'is_sample' => false,
                    'summary' => 'How to reset your dopamine sensitivity and make difficult, high-leverage tasks feel genuinely engaging and rewarding.',
                    'topics' => ['Dopamine Resets in 72 Hours', 'Eliminating Cheap Novelty', 'The Friction Matrix', 'Caffeine Timing Protocols'],
                ],
                [
                    'number' => '03',
                    'title' => 'Rituals for Deep Cognitive Flow',
                    'pages' => 'pp. 99–152',
                    'is_sample' => false,
                    'summary' => 'Binaural soundscapes, environmental anchoring, visual boundary setting, and biochemical triggers for rapid flow induction.',
                    'topics' => ['Environmental Flow Triggers', 'The 40Hz Gamma Stimulation', 'Visual Gaze Focus and Cognitive Alertness', 'Pre-Focus Warmup Rituals'],
                ],
                [
                    'number' => '04',
                    'title' => 'The 90-Minute Focus Sprint Blueprint',
                    'pages' => 'pp. 153–204',
                    'is_sample' => false,
                    'summary' => 'Structuring your workday around ultradian rhythms rather than artificial 8-hour industrial schedules.',
                    'topics' => ['Ultradian Biological Cycles', 'Active Recovery Between Sprints', 'Preventing Mental Burnout', 'Managing Context Switches'],
                ],
                [
                    'number' => '05',
                    'title' => 'Sustaining Peak Mental Clarity for Life',
                    'pages' => 'pp. 205–256',
                    'is_sample' => false,
                    'summary' => 'Sleep architecture, sunlight exposure, nutrition for neurotransmitter synthesis, and lifelong neuroplasticity maintenance.',
                    'topics' => ['Adenosine Clearance in Slow-Wave Sleep', 'Morning Photons & Cortisol Peak', 'Nootropics & Dietary Levers', 'Long-Term Neurogenesis'],
                ],
            ],
            'sample_content' => [
                'chapter_title' => 'Chapter 1: The Attentional Bottleneck',
                'reading_time' => '10 min read',
                'intro' => 'Your attention is not merely a cognitive resource—it is the single substrate through which your entire reality, productivity, and happiness are constructed. When you surrender control of your attention, you surrender authorship of your life.',
                'sections' => [
                    [
                        'heading' => 'The Cost of the “Quick Check”',
                        'content' => 'When you pause a complex programming or writing task to check a Slack ping or notification for just 15 seconds, your prefrontal cortex does not instantly snap back. Neuroscientists refer to this as “attention residue”: fragments of your cognitive capacity remain stuck on the previous stimulus for up to 23 minutes.',
                    ],
                    [
                        'heading' => 'The Task-Positive Network',
                        'content' => 'When in deep work, your brain activates the Task-Positive Network (TPN), suppressing self-referential doubt and distraction. To enter this state reliably, you must engineer an environment where distraction is physically harder to access than deep focus.',
                    ],
                    [
                        'heading' => 'The 3 Rules of Cognitive Sanctuary',
                        'content' => '1. No device with notifications inside your visual field during sprint hours. 2. Establish a singular, unambiguous goal before touching the keyboard. 3. Treat your focus like an Olympic muscle that strengthens with every resisted impulse.',
                    ],
                ],
            ],
        ],
        [
            'id' => 5,
            'slug' => 'quantum-frontiers-next-century',
            'title' => 'Quantum Frontiers: Next Century',
            'subtitle' => 'Topological Superconductors, Qubit Teleportation & The Dawn of Quantum Computation',
            'author' => 'Dr. Evelyn Vance',
            'author_role' => 'Quantum Physicist & Author',
            'author_bio' => 'Dr. Evelyn Vance is a senior quantum researcher at the European Institute for Theoretical Physics. She has authored foundational papers on topological insulators, fault-tolerant quantum algorithms, and room-temperature quantum coherence.',
            'author_avatar' => 'EV',
            'category' => 'Sci-Fi & Fantasy',
            'category_slug' => 'sci-fi-fantasy',
            'price' => '₹699',
            'original_price' => '₹1,499',
            'discount' => '53% OFF',
            'rating' => '5.0',
            'reviews' => '4,820',
            'rating_breakdown' => ['5' => 96, '4' => 3, '3' => 1, '2' => 0, '1' => 0],
            'image' => 'images/books/spotlight.jpg',
            'pages' => 480,
            'language' => 'English',
            'format' => 'PDF, EPUB & MOBI',
            'file_size' => '22.5 MB',
            'release_date' => 'March 2026',
            'edition' => 'Spotlight Special Edition',
            'isbn' => '978-0-691-18239-0',
            'badge' => 'Spotlight Book',
            'sample_pages' => 36,
            'description' => 'A breathtaking expedition through quantum computing, entanglement, multi-verse topologies, and the future of human consciousness. Synthesizes cutting-edge theoretical physics with speculative visions of the 22nd century and post-silicon civilization.',
            'prerequisites' => 'Written in an accessible, visually rich style with clear analogies. Suitable for curious science enthusiasts, technologists, and researchers alike.',
            'target_personas' => [
                [
                    'icon' => 'fa-solid fa-atom',
                    'title' => 'Quantum & Tech Enthusiasts',
                    'desc' => 'Understanding how quantum supremacy and qubit computers will transform encryption, AI, and material science.',
                ],
                [
                    'icon' => 'fa-solid fa-shield-halved',
                    'title' => 'Cybersecurity & Cryptography Experts',
                    'desc' => 'Preparing for post-quantum encryption standards, lattice cryptography, and quantum key distribution.',
                ],
                [
                    'icon' => 'fa-solid fa-infinity',
                    'title' => 'Philosophers & Deep Thinkers',
                    'desc' => 'Exploring the implications of quantum entanglement, superposition, and the nature of conscious observers.',
                ],
            ],
            'highlights' => [
                'Comprehensive guide to quantum state teleportation, entanglement, and fault-tolerant qubit architectures',
                'Exploring topological insulators, Majorana fermions, and room-temperature superconductors',
                'Ethical and technological horizons of quantum encrypted civilizations and post-RSA security',
            ],
            'chapters' => [
                [
                    'number' => '01',
                    'title' => 'Entanglement Across Light Years',
                    'pages' => 'pp. 1–90',
                    'is_sample' => true,
                    'summary' => 'The foundational physics of Bell state pairs, non-locality, and quantum communication channels.',
                    'topics' => ['EPR Paradox Resolved', 'Bell Inequalities Testing', 'Quantum Key Distribution (QKD)', 'Entanglement Swapping'],
                ],
                [
                    'number' => '02',
                    'title' => 'Qubit Superposition & Machine Intelligence',
                    'pages' => 'pp. 91–210',
                    'is_sample' => false,
                    'summary' => 'How quantum phase estimation and Grover/Shor algorithms surpass classical supercomputers exponentially.',
                    'topics' => ['Bloch Sphere Geometries', 'Quantum Gate Matrices', 'Shor’s Factoring Algorithm', 'Variational Quantum Eigensolvers'],
                ],
                [
                    'number' => '03',
                    'title' => 'The Holographic Universe Hypothesis',
                    'pages' => 'pp. 211–340',
                    'is_sample' => false,
                    'summary' => 'AdS/CFT correspondence and the radical theory that 3D spacetime is encoded on a 2D quantum boundary.',
                    'topics' => ['Black Hole Information Paradox', 'AdS/CFT Duality', 'Quantum Error Correction in Spacetime', 'Emergent Gravity'],
                ],
                [
                    'number' => '04',
                    'title' => 'Quantum Networks and the Post-Silicon Age',
                    'pages' => 'pp. 341–480',
                    'is_sample' => false,
                    'summary' => 'The transition from silicon transistors to topological qubits and the global quantum internet infrastructure.',
                    'topics' => ['Cryogenic Qubit Scaling', 'Topological Quantum Memory', 'Satellite Quantum Relays', 'Societal Horizons'],
                ],
            ],
            'sample_content' => [
                'chapter_title' => 'Chapter 1: Entanglement Across Light Years',
                'reading_time' => '16 min read',
                'intro' => 'When Albert Einstein famously dismissed quantum entanglement as “spooky action at a distance,” he could not have foreseen that within a century, this very anomaly would become the backbone of our next computational revolution.',
                'sections' => [
                    [
                        'heading' => 'The Non-Local Universe',
                        'content' => 'Two entangled particles share a single, indivisible wave function. When you measure the spin of particle A in a laboratory in Tokyo, the state of particle B—even if situated on the opposite side of the solar system—instantly collapses into the complementary state.',
                    ],
                    [
                        'heading' => 'Beyond Classical Bits',
                        'content' => 'A classical bit is constrained to either 0 or 1. A qubit exists in a continuous linear superposition of both states simultaneously. With just 300 perfectly entangled qubits, a quantum machine can represent more simultaneous states than there are subatomic particles in the observable universe.',
                    ],
                    [
                        'heading' => 'The Post-Quantum Era',
                        'content' => 'The race for fault-tolerant quantum computation is not merely an academic endeavor. It represents the defining inflection point in human cryptographic defense, molecular simulation, and fundamental physics.',
                    ],
                ],
            ],
        ],
        [
            'id' => 6,
            'slug' => 'designing-distributed-systems',
            'title' => 'Designing Distributed Systems',
            'subtitle' => 'Consensus Protocols, Event-Driven Architectures & Zero-Downtime High Availability at Scale',
            'author' => 'Brendan Caldwell',
            'author_role' => 'Infrastructure Engineering Director',
            'author_bio' => 'Brendan Caldwell leads distributed infrastructure engineering for global cloud providers, having architected systems processing over 50 billion events daily across multi-region datacenters.',
            'author_avatar' => 'BC',
            'category' => 'Tech & Coding',
            'category_slug' => 'tech-coding',
            'price' => '₹649',
            'original_price' => '₹1,299',
            'discount' => '50% OFF',
            'rating' => '5.0',
            'reviews' => '3,110',
            'rating_breakdown' => ['5' => 93, '4' => 6, '3' => 1, '2' => 0, '1' => 0],
            'image' => 'images/books/spotlight.jpg',
            'pages' => 380,
            'language' => 'English',
            'format' => 'PDF, EPUB & MOBI',
            'file_size' => '16.8 MB',
            'release_date' => 'January 2026',
            'edition' => '2nd Edition',
            'isbn' => '978-1-4920-3171-0',
            'badge' => 'Architecture Pick',
            'sample_pages' => 30,
            'description' => 'Practical blueprints for building high-availability, fault-tolerant distributed backends. Covers consensus protocols (Raft, Paxos), event sourcing, CQRS, and sharding architectures that endure extreme scale and network partitions without data loss.',
            'prerequisites' => 'Solid understanding of backend web services, databases (SQL/NoSQL), and basic networking concepts.',
            'target_personas' => [
                [
                    'icon' => 'fa-solid fa-network-wired',
                    'title' => 'Backend & Cloud Architects',
                    'desc' => 'Building microservices that survive split-brain scenarios and catastrophic datacenter failures.',
                ],
                [
                    'icon' => 'fa-solid fa-database',
                    'title' => 'Data Platform Engineers',
                    'desc' => 'Implementing event streams, write-ahead logs, and multi-leader database replication clusters.',
                ],
                [
                    'icon' => 'fa-solid fa-server',
                    'title' => 'DevOps & SRE Leads',
                    'desc' => 'Achieving 99.999% uptime with automated canary deployments and zero-downtime database migrations.',
                ],
            ],
            'highlights' => [
                'Consensus algorithms and leader election in untrusted networks with Raft and Paxos step-by-step',
                'Designing high-throughput event-driven microservices with Kafka, RabbitMQ, and transactional outboxes',
                'Partitioning, multi-region replication, and consistency trade-offs (CAP and PACELC theorems in practice)',
            ],
            'chapters' => [
                [
                    'number' => '01',
                    'title' => 'Foundations of Distributed Consensus',
                    'pages' => 'pp. 1–68',
                    'is_sample' => true,
                    'summary' => 'Handling network partitions, clock drift, and leader election using modern consensus algorithms.',
                    'topics' => ['The Byzantine Generals Problem', 'Raft Leader Election Mechanics', 'Quorum Calculations', 'Split-Brain Prevention'],
                ],
                [
                    'number' => '02',
                    'title' => 'Event Sourcing & CQRS at Scale',
                    'pages' => 'pp. 69–158',
                    'is_sample' => false,
                    'summary' => 'Separating read and write models with append-only event logs, projection builders, and idempotency keys.',
                    'topics' => ['Append-Only Event Stores', 'Eventual Consistency Projections', 'Transactional Outbox Pattern', 'Idempotency at Scale'],
                ],
                [
                    'number' => '03',
                    'title' => 'High-Throughput Sharding Strategies',
                    'pages' => 'pp. 159–270',
                    'is_sample' => false,
                    'summary' => 'Consistent hashing, virtual nodes, cross-shard transactions, and rebalancing without downtime.',
                    'topics' => ['Consistent Hashing Algorithms', 'Virtual Node Balancing', 'Two-Phase Commit (2PC) Pitfalls', 'Saga Distributed Transactions'],
                ],
                [
                    'number' => '04',
                    'title' => 'Zero-Downtime Migration Patterns',
                    'pages' => 'pp. 271–380',
                    'is_sample' => false,
                    'summary' => 'Dual-writing, shadow traffic, feature flag telemetry, and schema evolution in production environments.',
                    'topics' => ['Expand-Contract Schema Migrations', 'Dark Traffic Mirroring', 'Circuit Breakers & Bulkheads', 'Chaos Engineering Tests'],
                ],
            ],
            'sample_content' => [
                'chapter_title' => 'Chapter 1: Foundations of Distributed Consensus',
                'reading_time' => '13 min read',
                'intro' => 'In a single-server world, state is simple. In a distributed cluster, network packets are delayed, servers crash without warning, and clocks drift. Building reliable systems requires mastering consensus.',
                'sections' => [
                    [
                        'heading' => 'The Illusion of Synchrony',
                        'content' => 'Networks are asynchronous. You can never distinguish between a server that has crashed and a server that is responding slowly due to garbage collection or packet loss.',
                    ],
                    [
                        'heading' => 'The Raft State Machine',
                        'content' => 'Raft decomposes consensus into three independent sub-problems: Leader Election, Log Replication, and Safety invariants. Understanding these three guarantees eliminates 99% of distributed bugs.',
                    ],
                ],
            ],
        ],
        [
            'id' => 7,
            'slug' => 'neural-networks-from-scratch',
            'title' => 'Neural Networks from Scratch',
            'subtitle' => 'Matrix Calculus, Backpropagation & Tensor Architectures without High-Level Frameworks',
            'author' => 'Dr. Aaron Meyer',
            'author_role' => 'Principal AI Research Scientist',
            'author_bio' => 'Dr. Aaron Meyer is a leading researcher in deep learning architectures and computational linear algebra, having authored foundational implementations for edge intelligence.',
            'author_avatar' => 'AM',
            'category' => 'Tech & Coding',
            'category_slug' => 'tech-coding',
            'price' => '₹549',
            'original_price' => '₹1,099',
            'discount' => '50% OFF',
            'rating' => '4.9',
            'reviews' => '1,890',
            'rating_breakdown' => ['5' => 93, '4' => 5, '3' => 2, '2' => 0, '1' => 0],
            'image' => 'images/books/spotlight.jpg',
            'pages' => 395,
            'language' => 'English',
            'format' => 'PDF, EPUB & MOBI',
            'file_size' => '17.2 MB',
            'release_date' => 'February 2026',
            'edition' => '1st Edition',
            'isbn' => '978-0-13-405829-9',
            'badge' => 'Trending AI',
            'sample_pages' => 30,
            'description' => 'Build complete, GPU-accelerated deep learning models from pure mathematics and raw Python/NumPy arrays without relying on black-box frameworks.',
            'highlights' => [
                'Derive backpropagation and chain rule from first principles with matrix calculus',
                'Implement attention mechanisms, convolutions, and transformers from scratch',
                'Optimize tensor memory layouts and forward-backward gradient execution',
            ],
            'chapters' => [
                ['number' => '01', 'title' => 'Linear Algebra & Computational Graphs', 'pages' => 'pp. 1–55', 'is_sample' => true],
                ['number' => '02', 'title' => 'The Mathematics of Backpropagation', 'pages' => 'pp. 56–120', 'is_sample' => false],
                ['number' => '03', 'title' => 'Convolutions & Spatial Feature Extraction', 'pages' => 'pp. 121–210', 'is_sample' => false],
                ['number' => '04', 'title' => 'Self-Attention & Transformer Blocks', 'pages' => 'pp. 211–395', 'is_sample' => false],
            ],
        ],
        [
            'id' => 8,
            'slug' => 'mastering-ui-and-typography',
            'title' => 'Mastering UI & Typography',
            'subtitle' => 'Micro-Interactions, Spatial Harmony & Modern Typography for High-Impact Web Interfaces',
            'author' => 'Chloe Laurent',
            'author_role' => 'Product Design Director & Typographer',
            'author_bio' => 'Chloe Laurent is an internationally recognized design director whose design systems have empowered digital products used by hundreds of millions of users.',
            'author_avatar' => 'CL',
            'category' => 'Design & Arts',
            'category_slug' => 'design-arts',
            'price' => '₹399',
            'original_price' => '₹799',
            'discount' => '50% OFF',
            'rating' => '4.8',
            'reviews' => '1,240',
            'rating_breakdown' => ['5' => 90, '4' => 8, '3' => 2, '2' => 0, '1' => 0],
            'image' => 'images/books/algorithms.jpg',
            'pages' => 280,
            'language' => 'English',
            'format' => 'PDF, EPUB & MOBI',
            'file_size' => '15.6 MB',
            'release_date' => 'January 2026',
            'edition' => '1st Edition',
            'isbn' => '978-0-13-405831-2',
            'badge' => 'Design Essential',
            'sample_pages' => 26,
            'description' => 'The definitive visual guide to crafting stunning, responsive interfaces with immaculate typographic scale, glassmorphism, and subtle micro-animations.',
            'highlights' => [
                'Typographic hierarchy, fluid type scales, and optical baseline alignment',
                'Color theory, dark mode luminance calibration, and contrast mastery',
                'Designing micro-interactions and motion curves that delight users',
            ],
            'chapters' => [
                ['number' => '01', 'title' => 'The Geometry of Type & Spatial Scales', 'pages' => 'pp. 1–48', 'is_sample' => true],
                ['number' => '02', 'title' => 'Color Harmony & Dark Mode Luminance', 'pages' => 'pp. 49–110', 'is_sample' => false],
                ['number' => '03', 'title' => 'Layout Rhythm & Glassmorphism Systems', 'pages' => 'pp. 111–195', 'is_sample' => false],
                ['number' => '04', 'title' => 'Micro-Interactions & Physics Animation', 'pages' => 'pp. 196–280', 'is_sample' => false],
            ],
        ],
        [
            'id' => 9,
            'slug' => 'the-psychology-of-money-craft',
            'title' => 'The Psychology of Money Craft',
            'subtitle' => 'Behavioral Economics, Wealth Architecture & The Mental Models of High-Net-Worth Decision Making',
            'author' => 'Kavita Iyer',
            'author_role' => 'Behavioral Economist & Wealth Strategist',
            'author_bio' => 'Kavita Iyer advises private equity firms and family offices on behavioral risk management, asymmetric capital compounding, and market psychology.',
            'author_avatar' => 'KI',
            'category' => 'Psychology',
            'category_slug' => 'psychology',
            'price' => '₹449',
            'original_price' => '₹899',
            'discount' => '50% OFF',
            'rating' => '4.9',
            'reviews' => '2,450',
            'rating_breakdown' => ['5' => 94, '4' => 5, '3' => 1, '2' => 0, '1' => 0],
            'image' => 'images/books/atomic.jpg',
            'pages' => 310,
            'language' => 'English',
            'format' => 'PDF, EPUB & MOBI',
            'file_size' => '13.4 MB',
            'release_date' => 'March 2026',
            'edition' => '1st Edition',
            'isbn' => '978-0-13-405835-0',
            'badge' => 'Bestseller',
            'sample_pages' => 24,
            'description' => 'Unpack the psychological biases and cognitive blindspots that dictate financial outcomes. Master risk asymmetry, wealth endurance, and peaceful capital compounding.',
            'highlights' => [
                'Overcoming loss aversion, FOMO, and status-seeking behavioral traps',
                'Constructing anti-fragile financial flywheels and asymmetric bets',
                'The mental models of compounding freedom and long-term peace of mind',
            ],
            'chapters' => [
                ['number' => '01', 'title' => 'The Illusion of Financial Rationality', 'pages' => 'pp. 1–50', 'is_sample' => true],
                ['number' => '02', 'title' => 'Asymmetric Risk & Anti-Fragility', 'pages' => 'pp. 51–125', 'is_sample' => false],
                ['number' => '03', 'title' => 'The Compounding Mindset vs Status Games', 'pages' => 'pp. 126–215', 'is_sample' => false],
                ['number' => '04', 'title' => 'Wealth as Freedom: The Final Frontier', 'pages' => 'pp. 216–310', 'is_sample' => false],
            ],
        ],
    ];

    /**
     * Display all e-books with search & filter capabilities
     */
    public function index(Request $request): View
    {
        $search = trim($request->query('search', ''));
        $categoryFilter = $request->query('category', 'all');

        $books = collect($this->allBooks);

        if (! empty($search)) {
            $books = $books->filter(function ($b) use ($search) {
                return str_contains(strtolower($b['title']), strtolower($search))
                    || str_contains(strtolower($b['author']), strtolower($search))
                    || str_contains(strtolower($b['category']), strtolower($search));
            });
        }

        if ($categoryFilter !== 'all' && ! empty($categoryFilter)) {
            $books = $books->filter(fn ($b) => $b['category_slug'] === $categoryFilter);
        }

        $categories = [
            ['name' => 'All Genres', 'slug' => 'all'],
            ['name' => 'Tech & Coding', 'slug' => 'tech-coding'],
            ['name' => 'Sci-Fi & Fantasy', 'slug' => 'sci-fi-fantasy'],
            ['name' => 'Business & Finance', 'slug' => 'business-finance'],
            ['name' => 'Psychology', 'slug' => 'psychology'],
            ['name' => 'Design & Arts', 'slug' => 'design-arts'],
        ];

        return view('frontend.books.index', [
            'books' => $books->values()->all(),
            'categories' => $categories,
            'activeCategory' => $categoryFilter,
            'searchQuery' => $search,
            'totalCount' => count($this->allBooks),
        ]);
    }

    /**
     * Display individual book detail page as a dedicated, high-converting Landing Page
     */
    public function show(string|int $identifier): View
    {
        $book = collect($this->allBooks)->first(function ($b) use ($identifier) {
            return (string) $b['id'] === (string) $identifier || ($b['slug'] ?? '') === (string) $identifier;
        });

        if (! $book) {
            // Default fallback to first book if not matched
            $book = $this->allBooks[0];
        }

        // Compute tailored audience & category persona
        $categoryPersonas = [
            'tech-coding' => [
                'audience' => 'Software Engineers, Systems Architects, CS Students & Tech Leads',
                'badge' => 'Engineering Excellence',
                'author_expertise' => 'Over 15+ years architecting hyper-scale infrastructure and mentoring engineering teams worldwide.',
                'quote' => '“Simplicity is prerequisite for reliability. Master the underlying computational patterns, and the scale takes care of itself.”',
            ],
            'sci-fi-fantasy' => [
                'audience' => 'Hard Sci-Fi Enthusiasts, Speculative Fiction Readers & Worldbuilding Aficionados',
                'badge' => 'Speculative Masterpiece',
                'author_expertise' => 'Acclaimed author recognized for rigorous relativistic physics worldbuilding and rich character-driven storytelling.',
                'quote' => '“At the boundary of cosmic discovery, the universe does not reveal answers—it forces humanity to ask entirely new questions.”',
            ],
            'business-finance' => [
                'audience' => 'Founders, Executives, Operators, Product Leaders & Investors',
                'badge' => 'Executive Playbook',
                'author_expertise' => 'Serial entrepreneur and investor who has built and advised high-growth compounding ventures across global markets.',
                'quote' => '“Compounding is the greatest leverage in business. Design systems where every customer and iteration makes the next 10x easier.”',
            ],
            'psychology' => [
                'audience' => 'Knowledge Workers, High Performers, Researchers & Lifelong Learners',
                'badge' => 'Cognitive Mastery',
                'author_expertise' => 'Neuroscientist and behavioral consultant specializing in peak cognitive endurance and attention architecture.',
                'quote' => '“Focus is not about willpower—it is about biological architecture and eliminating cognitive friction before it begins.”',
            ],
            'design-arts' => [
                'audience' => 'Product Designers, UI/UX Specialists, Art Directors & Frontend Engineers',
                'badge' => 'Design Masterclass',
                'author_expertise' => 'Renowned design director and typographer whose visual systems shape award-winning products.',
                'quote' => '“Great design is invisible until you remove it. Visual rhythm and geometric cadence create intuitive resonance.”',
            ],
        ];

        $persona = $categoryPersonas[$book['category_slug'] ?? 'tech-coding'] ?? $categoryPersonas['tech-coding'];

        $landingData = [
            'reading_time' => sprintf('~%.1f Hours', max(2.5, ($book['pages'] ?? 300) / 65)),
            'target_audience' => $persona['audience'],
            'landing_badge' => $persona['badge'],
            'author_bio' => $book['author_bio'] ?? ($book['author'].' is a leading voice in '.($book['category'] ?? 'the field').'. '.$persona['author_expertise']),
            'book_quote' => $persona['quote'],
            'takeaways' => [
                [
                    'icon' => 'fa-solid fa-lightbulb',
                    'title' => 'Core Mental Models & Principles',
                    'desc' => 'Understand the foundational theory and mental frameworks that separate surface-level knowledge from deep mastery.',
                ],
                [
                    'icon' => 'fa-solid fa-layer-group',
                    'title' => 'Battle-Tested Actionable Frameworks',
                    'desc' => 'Zero academic filler. Every chapter delivers concrete templates, diagrams, and blueprints you can apply immediately.',
                ],
                [
                    'icon' => 'fa-solid fa-chart-line',
                    'title' => 'Proven Case Studies & Real Scenarios',
                    'desc' => 'Deconstruct real-world challenges, trade-offs, and breakdown points with detailed step-by-step walkthroughs.',
                ],
                [
                    'icon' => 'fa-solid fa-gem',
                    'title' => 'Compounding Long-Term Leverage',
                    'desc' => 'Gain timeless principles and competitive advantages designed to compound in value across your entire career.',
                ],
            ],
            'bonuses' => [
                [
                    'icon' => 'fa-solid fa-file-pdf',
                    'title' => 'Universal Multi-Format Bundle',
                    'desc' => 'DRM-free PDF & reflowable EPUB optimized for Kindle, iPad, Kobo, and desktop readers.',
                    'value' => 'Included ($19 Value)',
                ],
                [
                    'icon' => 'fa-solid fa-file-lines',
                    'title' => 'Executive Summary Cheat-Sheet',
                    'desc' => 'Printable high-resolution reference sheet summarizing every key concept and framework.',
                    'value' => 'Free Bonus ($29 Value)',
                ],
                [
                    'icon' => 'fa-solid fa-list-check',
                    'title' => 'Interactive Study & Action Checklist',
                    'desc' => 'Step-by-step implementation guide to turn book insights into daily execution.',
                    'value' => 'Free Bonus ($19 Value)',
                ],
                [
                    'icon' => 'fa-solid fa-arrows-rotate',
                    'title' => 'Lifetime Free Edition Updates',
                    'desc' => 'All future revised editions, errata corrections, and additional chapters delivered automatically.',
                    'value' => 'Lifetime Access',
                ],
            ],
            'faqs' => [
                [
                    'q' => 'What digital formats are provided upon purchase?',
                    'a' => 'You will receive instant, DRM-free downloads in both high-resolution PDF (with standard & dark reading modes) and reflowable EPUB format compatible with Kindle, Apple Books, Kobo, Android, and all modern e-readers.',
                ],
                [
                    'q' => 'How can I download and read the free sample preview?',
                    'a' => 'Click the "Download Free Sample (PDF)" button or "Look Inside Preview" button on this page. You will get immediate access to the Table of Contents, Author Introduction, and the full Chapter 1 sample with zero sign-up required.',
                ],
                [
                    'q' => 'How quickly will I get access to the complete e-book?',
                    'a' => 'Instantly. As soon as your checkout is confirmed, your download links are presented on-screen and sent directly to your email for permanent lifetime access.',
                ],
                [
                    'q' => 'Is this e-book suitable for beginners as well as advanced readers?',
                    'a' => 'Yes. The author structures each chapter progressively—starting from intuitive, practical fundamentals before exploring advanced edge cases and nuanced strategies.',
                ],
                [
                    'q' => 'Are future editions and bonus updates included?',
                    'a' => 'Yes! When you acquire this e-book, you receive lifetime access. Any future updates, revised editions, and supplemental materials are provided at zero additional cost.',
                ],
                [
                    'q' => 'What is your refund policy?',
                    'a' => 'We offer a 100% money-back guarantee within 30 days of purchase if you feel the e-book did not deliver immense value to your work or learning.',
                ],
            ],
        ];

        $reviews = [
            [
                'id' => 1,
                'name' => 'Alexander Hayes',
                'avatar' => 'AH',
                'rating' => 5,
                'date' => '3 days ago',
                'verified' => true,
                'title' => 'An absolute masterclass in practical execution',
                'comment' => 'This e-book exceeded all my expectations. The structured mental models and real-world case studies made it effortless to apply the concepts immediately to our strategic roadmap.',
                'helpful' => 28,
            ],
            [
                'id' => 2,
                'name' => 'Priya Sharma',
                'avatar' => 'PS',
                'rating' => 5,
                'date' => '1 week ago',
                'verified' => true,
                'title' => 'Crisp, concise, and zero fluff',
                'comment' => 'Unlike many publications that pad pages, every single chapter here delivers high signal-to-noise ratio. The diagrams and highlighted takeaways are invaluable.',
                'helpful' => 19,
            ],
            [
                'id' => 3,
                'name' => 'Marcus Vance',
                'avatar' => 'MV',
                'rating' => 5,
                'date' => '2 weeks ago',
                'verified' => true,
                'title' => 'Remarkable clarity on complex topics',
                'comment' => 'Loved how systematically the author breaks down nuance. Great formatting on iPad and Kindle readers alike. Highly recommended for anyone looking to level up.',
                'helpful' => 12,
            ],
            [
                'id' => 4,
                'name' => 'Elena Rostova',
                'avatar' => 'ER',
                'rating' => 5,
                'date' => '3 weeks ago',
                'verified' => true,
                'title' => 'The best investment in my learning this year',
                'comment' => 'I downloaded the free preview first and was hooked by page 10. Bought the full version immediately. The actionable frameworks and cheat sheets alone are worth triple the price.',
                'helpful' => 15,
            ],
            [
                'id' => 5,
                'name' => 'David Miller',
                'avatar' => 'DM',
                'rating' => 5,
                'date' => '1 month ago',
                'verified' => true,
                'title' => 'A definitive guide that simplifies hard problems',
                'comment' => 'Brilliant breakdown of core principles without overwhelming jargon. The practical examples and exercises cemented every single technique.',
                'helpful' => 21,
            ],
            [
                'id' => 6,
                'name' => 'Sofia Chen',
                'avatar' => 'SC',
                'rating' => 5,
                'date' => '1 month ago',
                'verified' => true,
                'title' => 'Transformed our entire team workflow',
                'comment' => 'We circulated this across our senior team. The depth and clarity are top-tier. Essential reading for anyone serious about mastery.',
                'helpful' => 17,
            ],
        ];

        // 8 Tailored "Suggested For" Target Personas per Category
        $suggestedProfiles = [
            'tech-coding' => [
                ['title' => 'Software Engineers', 'icon' => 'fa-solid fa-code'],
                ['title' => 'System Architects', 'icon' => 'fa-solid fa-server'],
                ['title' => 'CS Students', 'icon' => 'fa-solid fa-graduation-cap'],
                ['title' => 'Backend Developers', 'icon' => 'fa-solid fa-database'],
                ['title' => 'Tech Leads & CTOs', 'icon' => 'fa-solid fa-laptop-code'],
                ['title' => 'Self-Taught Devs', 'icon' => 'fa-solid fa-terminal'],
                ['title' => 'Performance Leads', 'icon' => 'fa-solid fa-gauge-high'],
                ['title' => 'Interview Preppers', 'icon' => 'fa-solid fa-briefcase'],
            ],
            'sci-fi-fantasy' => [
                ['title' => 'Sci-Fi Enthusiasts', 'icon' => 'fa-solid fa-rocket'],
                ['title' => 'Space Opera Fans', 'icon' => 'fa-solid fa-shuttle-space'],
                ['title' => 'Speculative Readers', 'icon' => 'fa-solid fa-atom'],
                ['title' => 'Worldbuilding Buffs', 'icon' => 'fa-solid fa-compass'],
                ['title' => 'Cosmology Lovers', 'icon' => 'fa-solid fa-sun'],
                ['title' => 'Fiction Writers', 'icon' => 'fa-solid fa-feather'],
                ['title' => 'Audiobook Fans', 'icon' => 'fa-solid fa-headphones'],
                ['title' => 'Book Club Members', 'icon' => 'fa-solid fa-users'],
            ],
            'business-finance' => [
                ['title' => 'Startup Founders', 'icon' => 'fa-solid fa-rocket'],
                ['title' => 'Solopreneurs', 'icon' => 'fa-solid fa-user-tie'],
                ['title' => 'Product Managers', 'icon' => 'fa-solid fa-chart-pie'],
                ['title' => 'Angel Investors', 'icon' => 'fa-solid fa-sack-dollar'],
                ['title' => 'SaaS Operators', 'icon' => 'fa-solid fa-cloud'],
                ['title' => 'Growth Strategists', 'icon' => 'fa-solid fa-arrow-trend-up'],
                ['title' => 'Marketing Leads', 'icon' => 'fa-solid fa-bullhorn'],
                ['title' => 'Executive Leaders', 'icon' => 'fa-solid fa-building'],
            ],
            'psychology' => [
                ['title' => 'Knowledge Workers', 'icon' => 'fa-solid fa-brain'],
                ['title' => 'High Performers', 'icon' => 'fa-solid fa-bolt'],
                ['title' => 'Students & Researchers', 'icon' => 'fa-solid fa-graduation-cap'],
                ['title' => 'Creative Writers', 'icon' => 'fa-solid fa-pen-nib'],
                ['title' => 'Habit Builders', 'icon' => 'fa-solid fa-repeat'],
                ['title' => 'Deep Thinkers', 'icon' => 'fa-solid fa-spa'],
                ['title' => 'Remote Professionals', 'icon' => 'fa-solid fa-laptop-house'],
                ['title' => 'Deep Work Pros', 'icon' => 'fa-solid fa-bullseye'],
            ],
            'design-arts' => [
                ['title' => 'UI/UX Designers', 'icon' => 'fa-solid fa-palette'],
                ['title' => 'Frontend Engineers', 'icon' => 'fa-solid fa-code'],
                ['title' => 'Product Designers', 'icon' => 'fa-solid fa-shapes'],
                ['title' => 'Art Directors', 'icon' => 'fa-solid fa-wand-magic-sparkles'],
                ['title' => 'Typographers', 'icon' => 'fa-solid fa-font'],
                ['title' => 'Design System Leads', 'icon' => 'fa-solid fa-layer-group'],
                ['title' => 'Digital Artists', 'icon' => 'fa-solid fa-brush'],
                ['title' => 'Creative Freelancers', 'icon' => 'fa-solid fa-crop-simple'],
            ],
        ];

        $suggestedFor = $suggestedProfiles[$book['category_slug'] ?? 'tech-coding'] ?? $suggestedProfiles['tech-coding'];

        return view('frontend.books.show', [
            'book' => $book,
            'landing' => $landingData,
            'reviews' => $reviews,
            'suggestedFor' => $suggestedFor,
        ]);
    }

    /**
     * Download or view book sample preview
     */
    public function downloadPreview(string|int $identifier): Response
    {
        $book = collect($this->allBooks)->first(function ($b) use ($identifier) {
            return (string) $b['id'] === (string) $identifier || ($b['slug'] ?? '') === (string) $identifier;
        });

        if (! $book) {
            $book = $this->allBooks[0];
        }

        $filename = ($book['slug'] ?? 'ebook').'-sample-preview.html';

        $html = view('frontend.books.preview-download', [
            'book' => $book,
        ])->render();

        return response($html)
            ->header('Content-Type', 'text/html; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }
}
