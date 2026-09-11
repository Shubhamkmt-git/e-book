<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    /**
     * Display a listing of testimonials.
     */
    public function index(Request $request): View
    {
        $query = Testimonial::with('book');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('profession', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%")
                    ->orWhereHas('book', function ($bq) use ($search) {
                        $bq->where('title', 'like', "%{$search}%");
                    });
            });
        }

        $testimonials = $query->orderBy('sort_order')->orderByDesc('created_at')->paginate(15)->withQueryString();
        $totalCount = Testimonial::count();

        return view('admin.testimonials.index', compact('testimonials', 'totalCount'));
    }

    /**
     * Show the form for creating a new testimonial.
     */
    public function create(): View
    {
        $books = Book::orderBy('title')->get(['id', 'title', 'author_name']);

        return view('admin.testimonials.create', compact('books'));
    }

    /**
     * Store a newly created testimonial.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'profession' => ['nullable', 'string', 'max:255'],
            'book_id' => ['nullable', 'exists:books,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'message' => ['required', 'string', 'max:2000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Testimonial::create([
            'name' => $validated['name'],
            'profession' => $validated['profession'] ?? null,
            'book_id' => $validated['book_id'] ?? null,
            'rating' => $validated['rating'],
            'message' => $validated['message'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial created successfully.');
    }

    /**
     * Display the specified testimonial.
     */
    public function show(Testimonial $testimonial): View
    {
        $testimonial->load('book');

        return view('admin.testimonials.show', compact('testimonial'));
    }

    /**
     * Show the form for editing the specified testimonial.
     */
    public function edit(Testimonial $testimonial): View
    {
        $books = Book::orderBy('title')->get(['id', 'title', 'author_name']);

        return view('admin.testimonials.edit', compact('testimonial', 'books'));
    }

    /**
     * Update the specified testimonial.
     */
    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'profession' => ['nullable', 'string', 'max:255'],
            'book_id' => ['nullable', 'exists:books,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'message' => ['required', 'string', 'max:2000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $testimonial->name = $validated['name'];
        $testimonial->profession = $validated['profession'] ?? null;
        $testimonial->book_id = $validated['book_id'] ?? null;
        $testimonial->rating = $validated['rating'];
        $testimonial->message = $validated['message'];
        $testimonial->sort_order = $validated['sort_order'] ?? 0;
        $testimonial->is_active = $request->boolean('is_active', true);
        $testimonial->save();

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial updated successfully.');
    }

    /**
     * Toggle the active status of a testimonial.
     */
    public function toggleStatus(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update(['is_active' => ! $testimonial->is_active]);

        return back()->with('success', 'Testimonial status updated.');
    }

    /**
     * Remove the specified testimonial.
     */
    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial deleted successfully.');
    }
}
