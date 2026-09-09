<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Complete E-Books catalogue collection with rich details
     */
    protected array $allBooks = [
        [
            'id' => 1,
            'slug' => 'algorithms-and-elegance',
            'title' => 'Algorithms & Elegance',
            'author' => 'Prof. Julian Hayes',
            'author_role' => 'Principal Systems Architect & CS Faculty',
            'category' => 'Tech & Coding',
            'category_slug' => 'tech-coding',
            'price' => '₹499',
            'original_price' => '₹999',
            'discount' => '50% OFF',
            'rating' => '4.9',
            'reviews' => '1,420',
            'image' => 'images/books/algorithms.jpg',
            'pages' => 412,
            'language' => 'English',
            'format' => 'PDF, EPUB & MOBI',
            'file_size' => '18.4 MB',
            'release_date' => 'January 2026',
            'isbn' => '978-0-13-405822-1',
            'badge' => 'Bestseller',
            'description' => 'A masterclass in crafting resilient, performant, and elegant code. Delve into advanced data structures, algorithmic design patterns, computational complexity, and modern software paradigms designed to scale from zero to millions of operations per second.',
            'highlights' => [
                'Master dynamic programming, graph theory, and algorithmic time complexity',
                'Production-grade implementation examples in Python, Go, and TypeScript',
                'Design robust distributed caching and cache invalidation strategies',
                'Deep dive into memory layout, CPU cache locality, and parallel execution',
                'Real-world case studies from high-frequency and hyper-scale infrastructure',
            ],
            'chapters' => [
                '01. Computational Complexity & Algorithmic Thinking',
                '02. Advanced Trees, Heaps, and Spatial Indexing',
                '03. Graph Traversals & Network Optimization',
                '04. Dynamic Programming Deconstructed',
                '05. Distributed State & Concurrency Patterns',
                '06. Architecture for Ultra-Low Latency Pipelines',
            ],
        ],
        [
            'id' => 2,
            'slug' => 'whispers-of-the-nebula',
            'title' => 'Whispers of the Nebula',
            'author' => 'S. K. Hawthorne',
            'author_role' => 'Nebula & Hugo Award Nominated Author',
            'category' => 'Sci-Fi & Fantasy',
            'category_slug' => 'sci-fi-fantasy',
            'price' => '₹349',
            'original_price' => '₹699',
            'discount' => '50% OFF',
            'rating' => '4.8',
            'reviews' => '980',
            'image' => 'images/books/nebula.jpg',
            'pages' => 368,
            'language' => 'English',
            'format' => 'PDF, EPUB & MOBI',
            'file_size' => '14.1 MB',
            'release_date' => 'February 2026',
            'isbn' => '978-0-593-18234-5',
            'badge' => 'Featured Sci-Fi',
            'description' => 'In a forgotten sector on the galactic periphery, an orphaned signal analyst detects quantum transmissions originating from inside a supermassive black hole. What follows is a gripping interstellar odyssey through time fractures, cosmic relics, and deep-space intrigue.',
            'highlights' => [
                'Expansive hard science fiction world-building grounded in relativistic physics',
                'Multi-generational narrative arc across colonized star systems',
                'Complex characters navigating interplanetary politics and rogue synthetic intelligences',
                'Includes full-color illustrated cosmic star-charts and system schematics',
            ],
            'chapters' => [
                '01. The Horizon Array at Zero Point',
                '02. Quantum Echoes from the Event Horizon',
                '03. The Relic of Asterion Prime',
                '04. Slipstream Jump through Sector 9',
                '05. Sovereign Minds of the Outer Reach',
                '06. Beyond the Galactic Veil',
            ],
        ],
        [
            'id' => 3,
            'slug' => 'the-compound-founder',
            'title' => 'The Compound Founder',
            'author' => 'Marcus Bennett',
            'author_role' => 'Serial Entrepreneur & Early-Stage Investor',
            'category' => 'Business & Finance',
            'category_slug' => 'business-finance',
            'price' => '₹599',
            'original_price' => '₹1,199',
            'discount' => '50% OFF',
            'rating' => '5.0',
            'reviews' => '2,110',
            'image' => 'images/books/founder.jpg',
            'pages' => 294,
            'language' => 'English',
            'format' => 'PDF, EPUB & MOBI',
            'file_size' => '12.8 MB',
            'release_date' => 'January 2026',
            'isbn' => '978-1-59184-756-3',
            'badge' => 'Top Business Book',
            'description' => 'The definitive playbook for building high-margin, scalable companies with compounding leverage. Learn how exceptional founders systematically validate ideas, engineer flywheels, attract top-tier capital, and execute with ruthless operational clarity.',
            'highlights' => [
                'Frameworks for identifying uncrowded markets with immense pricing power',
                'Unit economics, customer acquisition loops, and compounding retention models',
                'How to structure executive teams and maintain high velocity at scale',
                'Term sheet negotiation blueprints and capital allocation principles',
            ],
            'chapters' => [
                '01. The Architecture of Compounding Moats',
                '02. Validating at the Edge of High Margin',
                '03. The Growth Engine: Loops over Funnels',
                '04. Capital Allocation & Venture Mechanics',
                '05. Culture, Execution, and Ruthless Focus',
            ],
        ],
        [
            'id' => 4,
            'slug' => 'atomic-focus',
            'title' => 'Atomic Focus',
            'author' => 'Dr. Aris Thorne',
            'author_role' => 'Cognitive Neuroscientist & Performance Consultant',
            'category' => 'Psychology',
            'category_slug' => 'psychology',
            'price' => '₹299',
            'original_price' => '₹599',
            'discount' => '50% OFF',
            'rating' => '4.9',
            'reviews' => '3,540',
            'image' => 'images/books/atomic.jpg',
            'pages' => 256,
            'language' => 'English',
            'format' => 'PDF, EPUB & MOBI',
            'file_size' => '9.7 MB',
            'release_date' => 'December 2025',
            'isbn' => '978-0-7352-1129-2',
            'badge' => 'Reader Choice',
            'description' => 'Reclaim your attention span and master deep cognitive work in an era designed for distraction. Dr. Thorne unpacks neuroscience-backed protocols for dopamine regulation, state-induced flow triggers, and bulletproof habit retention.',
            'highlights' => [
                'Neurochemical protocols for entering flow states on command within 15 minutes',
                'Strategies to eliminate digital fragmentation and cognitive context switching',
                'Designing high-leverage physical and digital workspaces for sustained focus',
                'Daily routines used by elite researchers, chess grandmasters, and top engineers',
            ],
            'chapters' => [
                '01. The Attentional Bottleneck',
                '02. Dopamine, Novelty, and Cognitive Fatigue',
                '03. Rituals for Deep Cognitive Flow',
                '04. The 90-Minute Focus Sprint Blueprint',
                '05. Sustaining Peak Mental Clarity for Life',
            ],
        ],
        [
            'id' => 5,
            'slug' => 'quantum-frontiers-next-century',
            'title' => 'Quantum Frontiers: Next Century',
            'author' => 'Dr. Evelyn Vance',
            'author_role' => 'Quantum Physicist & Author',
            'category' => 'Sci-Fi & Fantasy',
            'category_slug' => 'sci-fi-fantasy',
            'price' => '₹699',
            'original_price' => '₹1,499',
            'discount' => '53% OFF',
            'rating' => '5.0',
            'reviews' => '4,820',
            'image' => 'images/books/spotlight.jpg',
            'pages' => 480,
            'language' => 'English',
            'format' => 'PDF, EPUB & MOBI',
            'file_size' => '22.5 MB',
            'release_date' => 'March 2026',
            'isbn' => '978-0-691-18239-0',
            'badge' => 'Spotlight Book',
            'description' => 'A breathtaking expedition through quantum computing, entanglement, multi-verse topologies, and the future of human consciousness. Synthesizes cutting-edge theoretical physics with speculative visions of the 22nd century.',
            'highlights' => [
                'Comprehensive guide to quantum state teleportation and qubit architectures',
                'Exploring topological insulators and room-temperature superconductors',
                'Ethical and technological horizons of quantum encrypted civilizations',
            ],
            'chapters' => [
                '01. Entanglement Across Light Years',
                '02. Qubit Superposition & Machine Intelligence',
                '03. The Holographic Universe Hypothesis',
                '04. Quantum Networks and the Post-Silicon Age',
            ],
        ],
        [
            'id' => 6,
            'slug' => 'designing-distributed-systems',
            'title' => 'Designing Distributed Systems',
            'author' => 'Brendan Caldwell',
            'author_role' => 'Infrastructure Engineering Director',
            'category' => 'Tech & Coding',
            'category_slug' => 'tech-coding',
            'price' => '₹649',
            'original_price' => '₹1,299',
            'discount' => '50% OFF',
            'rating' => '5.0',
            'reviews' => '3,110',
            'image' => 'images/books/spotlight.jpg',
            'pages' => 380,
            'language' => 'English',
            'format' => 'PDF, EPUB & MOBI',
            'file_size' => '16.8 MB',
            'release_date' => 'January 2026',
            'isbn' => '978-1-4920-3171-0',
            'badge' => 'Architecture Pick',
            'description' => 'Practical blueprints for building high-availability, fault-tolerant distributed backends. Covers consensus protocols (Raft, Paxos), event sourcing, CQRS, and sharding architectures that endure extreme scale without data loss.',
            'highlights' => [
                'Consensus algorithms and leader election in untrusted networks',
                'Designing event-driven microservices with Kafka and RabbitMQ',
                'Partitioning, multi-region replication, and consistency trade-offs (CAP theorem)',
            ],
            'chapters' => [
                '01. Foundations of Distributed Consensus',
                '02. Event Sourcing & CQRS at Scale',
                '03. High-Throughput Sharding Strategies',
                '04. Zero-Downtime Migration Patterns',
            ],
        ],
        [
            'id' => 7,
            'slug' => 'zero-to-market-leader',
            'title' => 'Zero to Market Leader',
            'author' => 'Victoria Sterling',
            'author_role' => 'Growth Strategist & Former CMO',
            'category' => 'Business & Finance',
            'category_slug' => 'business-finance',
            'price' => '₹499',
            'original_price' => '₹999',
            'discount' => '50% OFF',
            'rating' => '4.9',
            'reviews' => '1,870',
            'image' => 'images/books/atomic.jpg',
            'pages' => 310,
            'language' => 'English',
            'format' => 'PDF, EPUB & MOBI',
            'file_size' => '11.4 MB',
            'release_date' => 'February 2026',
            'isbn' => '978-0-525-53831-8',
            'badge' => 'Growth Bible',
            'description' => 'How breakout products dominate crowded industries through category creation, narrative positioning, and viral product-led growth loops. Includes tactical playbooks tested on multiple unicorn startups.',
            'highlights' => [
                'Category creation vs. fighting for market share in red oceans',
                'Building viral referral hooks into product onboarding',
                'Enterprise B2B land-and-expand revenue frameworks',
            ],
            'chapters' => [
                '01. The Myth of the Better Product',
                '02. Crafting an Irresistible Strategic Narrative',
                '03. Product-Led Growth Engine Design',
                '04. Pricing for Exponential Expansion',
            ],
        ],
        [
            'id' => 8,
            'slug' => 'visual-geometry-in-modern-ui',
            'title' => 'Visual Geometry in Modern UI',
            'author' => 'Mateo Bianchi',
            'author_role' => 'Head of Design & Typography Specialist',
            'category' => 'Design & Arts',
            'category_slug' => 'design-arts',
            'price' => '₹499',
            'original_price' => '₹999',
            'discount' => '50% OFF',
            'rating' => '4.9',
            'reviews' => '1,560',
            'image' => 'images/books/algorithms.jpg',
            'pages' => 320,
            'language' => 'English',
            'format' => 'PDF, EPUB & MOBI',
            'file_size' => '28.2 MB',
            'release_date' => 'January 2026',
            'isbn' => '978-0-8118-6831-2',
            'badge' => 'Design Essential',
            'description' => 'An inspiring visual compendium illustrating how mathematical ratios, spatial grids, fluid typography, and dynamic contrast create interfaces that evoke emotional resonance and effortless usability.',
            'highlights' => [
                'Golden ratio and Fibonacci grids in modern responsive web design',
                'Micro-interactions and motion physics that feel organic',
                'Color science, perceptual contrast, and dark mode harmony',
            ],
            'chapters' => [
                '01. Geometric Harmony in Digital Layouts',
                '02. Spacing Scales & Visual Cadence',
                '03. Modern Fluid Typography Systems',
                '04. Color Theory & Accessibility',
            ],
        ],
        [
            'id' => 9,
            'slug' => 'full-stack-architecture-patterns',
            'title' => 'Full-Stack Architecture Patterns',
            'author' => 'Devon Vance',
            'author_role' => 'Staff Full-Stack Engineer',
            'category' => 'Tech & Coding',
            'category_slug' => 'tech-coding',
            'price' => '₹549',
            'original_price' => '₹1,099',
            'discount' => '50% OFF',
            'rating' => '4.9',
            'reviews' => '2,040',
            'image' => 'images/books/algorithms.jpg',
            'pages' => 395,
            'language' => 'English',
            'format' => 'PDF, EPUB & MOBI',
            'file_size' => '17.6 MB',
            'release_date' => 'February 2026',
            'isbn' => '978-1-4919-5462-1',
            'badge' => 'Tech Guide',
            'description' => 'Bridge the gap between frontend state mechanics and backend persistence. Learn clean architecture, modular monoliths, serverless integration, and type-safe API communication patterns.',
            'highlights' => [
                'Domain-Driven Design (DDD) applied to full-stack web applications',
                'Optimistic UI state management and offline-first syncing',
                'Secure authentication flows with OAuth2, PKCE, and JWTs',
            ],
            'chapters' => [
                '01. Decoupling Domain Logic from UI State',
                '02. Building High-Performance Data Layers',
                '03. API Design & Real-Time Syncing',
                '04. Testing Strategy: Unit to End-to-End',
            ],
        ],
        [
            'id' => 10,
            'slug' => 'the-typography-bible',
            'title' => 'The Typography Bible',
            'author' => 'Chloe Dubois',
            'author_role' => 'Editorial Director & Font Foundry Lead',
            'category' => 'Design & Arts',
            'category_slug' => 'design-arts',
            'price' => '₹599',
            'original_price' => '₹1,199',
            'discount' => '50% OFF',
            'rating' => '5.0',
            'reviews' => '2,430',
            'image' => 'images/books/spotlight.jpg',
            'pages' => 350,
            'language' => 'English',
            'format' => 'PDF, EPUB & MOBI',
            'file_size' => '31.5 MB',
            'release_date' => 'January 2026',
            'isbn' => '978-0-500-24155-4',
            'badge' => 'Collector Edition',
            'description' => 'The definitive guide to letterforms, editorial hierarchy, optical kerning, and typographic expression. Essential reading for every designer looking to make text communicate with power and grace.',
            'highlights' => [
                'Historical anatomy of classic and contemporary typefaces',
                'Pairing serif, sans-serif, and display fonts with precision',
                'Editorial grid layouts for magazines, books, and web apps',
            ],
            'chapters' => [
                '01. Anatomy of the Letterform',
                '02. Hierarchy, Measure, and Leading',
                '03. Font Pairing Masterclasses',
                '04. Digital Typography on Varied Displays',
            ],
        ],
        [
            'id' => 11,
            'slug' => 'the-neurochemistry-of-willpower',
            'title' => 'The Neurochemistry of Willpower',
            'author' => 'Dr. Samantha Cruz',
            'author_role' => 'Behavioral Biologist & Clinical Psychologist',
            'category' => 'Psychology',
            'category_slug' => 'psychology',
            'price' => '₹449',
            'original_price' => '₹899',
            'discount' => '50% OFF',
            'rating' => '5.0',
            'reviews' => '2,780',
            'image' => 'images/books/founder.jpg',
            'pages' => 280,
            'language' => 'English',
            'format' => 'PDF, EPUB & MOBI',
            'file_size' => '10.2 MB',
            'release_date' => 'December 2025',
            'isbn' => '978-0-14-312223-4',
            'badge' => 'Self Mastery',
            'description' => 'Discover how neurotransmitters govern motivation, impulse inhibition, and resilience. Learn scientifically grounded tactics to reprogram habitual triggers and unlock steadfast mental endurance.',
            'highlights' => [
                'How the prefrontal cortex mediates impulse control against emotional triggers',
                'Nutritional and circadian levers that fuel mental stamina',
                'Cognitive reframing tools to eliminate procrastination and fear of failure',
            ],
            'chapters' => [
                '01. The Brain Fuel Equation',
                '02. Habit Loops in the Basal Ganglia',
                '03. Emotional Friction & Willpower Leaks',
                '04. Building Unbreakable Daily Rituals',
            ],
        ],
        [
            'id' => 12,
            'slug' => 'the-cybernetic-odyssey',
            'title' => 'The Cybernetic Odyssey',
            'author' => 'Aria Sterling',
            'author_role' => 'Science Fiction Novelist & Futurist',
            'category' => 'Sci-Fi & Fantasy',
            'category_slug' => 'sci-fi-fantasy',
            'price' => '₹449',
            'original_price' => '₹899',
            'discount' => '50% OFF',
            'rating' => '4.9',
            'reviews' => '1,120',
            'image' => 'images/books/algorithms.jpg',
            'pages' => 340,
            'language' => 'English',
            'format' => 'PDF, EPUB & MOBI',
            'file_size' => '13.9 MB',
            'release_date' => 'March 2026',
            'isbn' => '978-0-441-01359-3',
            'badge' => 'Cyberpunk Epic',
            'description' => 'Set in a neon-drenched megacity where humanity and neural interfaces merge, an exiled codebreaker stumbles upon a sentient algorithm threatening to rewrite the fabric of digital identity.',
            'highlights' => [
                'Atmospheric cyberpunk world with high-stakes techno-philosophical mystery',
                'Deep dive into synthetic life, augmented perception, and autonomy',
                'Fast-paced action with vivid narrative world building',
            ],
            'chapters' => [
                '01. Neon Shadows in Sector 7',
                '02. The Neural Breach',
                '03. Codebreakers of the Dark Subnet',
                '04. The Sovereign Consciousness Awakens',
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
            'author_bio' => $book['author'].' is a leading voice in '.($book['category'] ?? 'the field').'. '.$persona['author_expertise'],
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
                    'title' => 'Universal Multi-Format Bundle',
                    'desc' => 'DRM-free PDF & reflowable EPUB optimized for Kindle, iPad, Kobo, and desktop readers.',
                    'value' => 'Included',
                ],
                [
                    'title' => 'Executive Summary Cheat-Sheet',
                    'desc' => 'Printable high-resolution reference sheet summarizing every key concept and framework.',
                    'value' => 'Free Bonus',
                ],
                [
                    'title' => 'Interactive Study & Action Checklist',
                    'desc' => 'Step-by-step implementation guide to turn book insights into daily execution.',
                    'value' => 'Free Bonus',
                ],
                [
                    'title' => 'Lifetime Free Edition Updates',
                    'desc' => 'All future revised editions, errata corrections, and additional chapters delivered automatically.',
                    'value' => 'Lifetime',
                ],
            ],
            'faqs' => [
                [
                    'q' => 'What digital formats are provided upon purchase?',
                    'a' => 'You will receive instant, DRM-free downloads in both high-resolution PDF (with standard & dark reading modes) and reflowable EPUB format compatible with Kindle, Apple Books, Kobo, Android, and all modern e-readers.',
                ],
                [
                    'q' => 'How quickly will I get access to the e-book?',
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
        ];

        return view('frontend.books.show', [
            'book' => $book,
            'landing' => $landingData,
            'reviews' => $reviews,
        ]);
    }
}
