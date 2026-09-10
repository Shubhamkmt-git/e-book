<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $techCat = Category::where('slug', 'technology-programming')->first();
        $scienceCat = Category::where('slug', 'science-space')->first();
        $bizCat = Category::where('slug', 'business-finance')->first();

// Remove any previously seeded books to avoid duplication
        Book::truncate();

        $books = [
            [
                'title' => 'The Art of Quantum Computing',
                'author_name' => 'Dr. Maya Patel',
                'category_id' => $techCat?->id,
                'price' => 1999.00,
                'selling_price' => 1199.00,
                'status' => 'active',
                'is_featured' => true,
                'description' => "An in‑depth guide that bridges quantum theory and practical algorithm design. Explore qubit architectures, quantum error correction, and real‑world applications from cryptography to optimization. Includes code snippets in Q# and Python's Qiskit, hands‑on labs, and interviews with leading researchers.",
                'key_highlights' => "Comprehensive quantum mechanics refresher\nStep‑by‑step implementation of quantum algorithms\nPractical guidance on using cloud‑based quantum processors\nCase studies from finance, chemistry, and AI",
                'table_of_contents' => "Part I: Foundations\n  - Chapter 1: Quantum States & Superposition\n  - Chapter 2: Entanglement & Measurement\nPart II: Algorithms\n  - Chapter 3: Grover's Search\n  - Chapter 4: Shor's Factoring\n  - Chapter 5: Variational Quantum Eigensolver\nPart III: Applications\n  - Chapter 6: Quantum Machine Learning\n  - Chapter 7: Quantum Chemistry Simulations\nPart IV: Future Directions\n  - Chapter 8: Error‑Corrected Quantum Computing\n  - Chapter 9: Quantum Internet",
                'suggested_for' => ['Quantum Computing Enthusiasts', 'Researchers & Academics', 'Developers & Engineers'],
            ],
        ];

        foreach ($books as $book) {
            $slug = Str::slug($book['title']);
            Book::updateOrCreate(
                ['slug' => $slug],
                array_merge($book, ['slug' => $slug])
            );
        }
    }
}
