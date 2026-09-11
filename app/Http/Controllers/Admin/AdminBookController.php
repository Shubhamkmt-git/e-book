<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminBookController extends Controller
{
    /**
     * Predefined suggested audiences with default icons.
     *
     * @return list<array{title: string, icon: string}>
     */
    protected function getAvailableSuggestedAudiences(): array
    {
        return [
            ['title' => 'Beginners & Starters', 'icon' => 'fa-solid fa-seedling'],
            ['title' => 'Students & Academics', 'icon' => 'fa-solid fa-graduation-cap'],
            ['title' => 'Software Engineers & Developers', 'icon' => 'fa-solid fa-laptop-code'],
            ['title' => 'Data Scientists & AI Practitioners', 'icon' => 'fa-solid fa-brain'],
            ['title' => 'Entrepreneurs & Business Leaders', 'icon' => 'fa-solid fa-briefcase'],
            ['title' => 'Designers & Creatives', 'icon' => 'fa-solid fa-palette'],
            ['title' => 'Researchers & Scientists', 'icon' => 'fa-solid fa-microscope'],
            ['title' => 'Self-Learners & Hobbyists', 'icon' => 'fa-solid fa-book-open-reader'],
            ['title' => 'Kids & Young Adults', 'icon' => 'fa-solid fa-child-reaching'],
            ['title' => 'General Readers & Enthusiasts', 'icon' => 'fa-solid fa-glasses'],
        ];
    }

    /**
     * Available icon options for custom target audiences.
     *
     * @return list<array{class: string, label: string}>
     */
    protected function getAvailableAudienceIcons(): array
    {
        return [
            ['class' => 'fa-solid fa-user-tag', 'label' => 'Audience Tag'],
            ['class' => 'fa-solid fa-users', 'label' => 'Community'],
            ['class' => 'fa-solid fa-user-graduate', 'label' => 'Graduate'],
            ['class' => 'fa-solid fa-laptop-code', 'label' => 'Programmer'],
            ['class' => 'fa-solid fa-code', 'label' => 'Developer'],
            ['class' => 'fa-solid fa-brain', 'label' => 'AI & Mind'],
            ['class' => 'fa-solid fa-briefcase', 'label' => 'Business'],
            ['class' => 'fa-solid fa-chart-line', 'label' => 'Finance & Growth'],
            ['class' => 'fa-solid fa-graduation-cap', 'label' => 'Academics'],
            ['class' => 'fa-solid fa-palette', 'label' => 'Design & Creative'],
            ['class' => 'fa-solid fa-microscope', 'label' => 'Science & Research'],
            ['class' => 'fa-solid fa-book-open-reader', 'label' => 'Self Learner'],
            ['class' => 'fa-solid fa-rocket', 'label' => 'Startups & Scale'],
            ['class' => 'fa-solid fa-lightbulb', 'label' => 'Innovators'],
            ['class' => 'fa-solid fa-bullseye', 'label' => 'Target & Goals'],
            ['class' => 'fa-solid fa-trophy', 'label' => 'Exams & Competition'],
            ['class' => 'fa-solid fa-shield-halved', 'label' => 'Security & Law'],
            ['class' => 'fa-solid fa-heart-pulse', 'label' => 'Healthcare'],
            ['class' => 'fa-solid fa-building-columns', 'label' => 'Civil / Govt'],
            ['class' => 'fa-solid fa-seedling', 'label' => 'Beginners'],
            ['class' => 'fa-solid fa-child-reaching', 'label' => 'Youth & Kids'],
            ['class' => 'fa-solid fa-glasses', 'label' => 'General Readers'],
            ['class' => 'fa-solid fa-user-gear', 'label' => 'Operations'],
            ['class' => 'fa-solid fa-compass', 'label' => 'Guides & Explorers'],
        ];
    }

    public function index(Request $request): View
    {
        $query = Book::with('category');

        if ($request->filled('search')) {
            $search = trim((string) $request->string('search'));
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author_name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', (string) $request->string('status'));
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', $request->string('featured') === '1');
        }

        $books = $query->latest('id')->paginate(12)->withQueryString();
        $categories = Category::orderBy('title')->get(['id', 'title']);

        return view('admin.books.index', [
            'books' => $books,
            'categories' => $categories,
            'totalCount' => Book::count(),
            'activeCount' => Book::where('status', 'active')->count(),
            'featuredCount' => Book::where('is_featured', true)->count(),
        ]);
    }

    public function create(): View
    {
        return view('admin.books.create', [
            'categories' => Category::where('status', 'active')->orderBy('title')->get(['id', 'title']),
            'suggestedAudiences' => $this->getAvailableSuggestedAudiences(),
            'availableIcons' => $this->getAvailableAudienceIcons(),
            'statuses' => ['active' => 'Active', 'inactive' => 'Inactive'],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        // Auto-generate slug from title
        $baseSlug = Str::slug((string) $request->string('title'));
        $request->merge(['slug' => $baseSlug]);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:books,slug'],
            'author_name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'selling_price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'is_featured' => ['boolean'],
            'description' => ['nullable', 'string'],
            'key_highlights' => ['nullable', 'string'],
            'table_of_contents' => ['nullable', 'string'],
            'suggested_for' => ['nullable', 'array'],
            'suggested_for.*' => ['string', 'max:150'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg,gif,avif'],
            'gallery_images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg,gif,avif'],
            'sample_file' => ['nullable', 'file', 'mimes:pdf,epub,doc,docx'],
            'ebook_file' => ['nullable', 'file', 'mimes:pdf,epub,mobi,zip,rar,doc,docx'],
            'pages' => ['nullable', 'integer', 'min:1', 'max:999999'],
            'language' => ['nullable', 'string', 'max:100'],
            'format' => ['nullable', 'string', 'max:100'],
            'file_size' => ['nullable', 'string', 'max:100'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['suggested_for'] = array_values(array_unique(array_filter(array_map('trim', (array) $request->input('suggested_for', [])))));

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('books/covers', 'public');
            $validated['cover_image'] = $path;
        }

        if ($request->hasFile('gallery_images')) {
            $galleryPaths = [];
            foreach ($request->file('gallery_images') as $file) {
                if ($file->isValid()) {
                    $galleryPaths[] = $file->store('books/gallery', 'public');
                }
            }
            if (! empty($galleryPaths)) {
                $validated['gallery_images'] = $galleryPaths;
            }
        }

        if ($request->hasFile('sample_file')) {
            $path = $request->file('sample_file')->store('books/samples', 'public');
            $validated['sample_file'] = $path;
        }

        if ($request->hasFile('ebook_file')) {
            $file = $request->file('ebook_file');
            $path = $file->store('books/ebooks', 'public');
            $validated['ebook_file'] = $path;

            // Auto-fill file_size if empty
            if (empty($validated['file_size'])) {
                $bytes = $file->getSize();
                $validated['file_size'] = $bytes >= 1048576
                    ? number_format($bytes / 1048576, 1).' MB'
                    : number_format($bytes / 1024, 1).' KB';
            }
        }

        Book::create($validated);

        return redirect()->route('admin.books.index')->with('success', 'E-Book created successfully.');
    }

    public function show(Book $book): View
    {
        $book->load('category');

        return view('admin.books.show', compact('book'));
    }

    public function edit(Book $book): View
    {
        return view('admin.books.edit', [
            'book' => $book,
            'categories' => Category::orderBy('title')->get(['id', 'title', 'status']),
            'suggestedAudiences' => $this->getAvailableSuggestedAudiences(),
            'availableIcons' => $this->getAvailableAudienceIcons(),
            'statuses' => ['active' => 'Active', 'inactive' => 'Inactive'],
        ]);
    }

    public function update(Request $request, Book $book): RedirectResponse
    {
        // Auto-generate slug from title
        $baseSlug = Str::slug((string) $request->string('title'));
        $request->merge(['slug' => $baseSlug]);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('books', 'slug')->ignore($book->id)],
            'author_name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'selling_price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'is_featured' => ['boolean'],
            'description' => ['nullable', 'string'],
            'key_highlights' => ['nullable', 'string'],
            'table_of_contents' => ['nullable', 'string'],
            'suggested_for' => ['nullable', 'array'],
            'suggested_for.*' => ['string', 'max:150'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg,gif,avif'],
            'gallery_images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg,gif,avif'],
            'sample_file' => ['nullable', 'file', 'mimes:pdf,epub,doc,docx'],
            'ebook_file' => ['nullable', 'file', 'mimes:pdf,epub,mobi,zip,rar,doc,docx'],
            'pages' => ['nullable', 'integer', 'min:1', 'max:999999'],
            'language' => ['nullable', 'string', 'max:100'],
            'format' => ['nullable', 'string', 'max:100'],
            'file_size' => ['nullable', 'string', 'max:100'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['suggested_for'] = array_values(array_unique(array_filter(array_map('trim', (array) $request->input('suggested_for', [])))));

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image && ! str_starts_with($book->cover_image, 'http') && ! str_starts_with($book->cover_image, 'images/')) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $path = $request->file('cover_image')->store('books/covers', 'public');
            $validated['cover_image'] = $path;
        }

        if ($request->hasFile('gallery_images')) {
            // Delete old gallery images if replacing
            if (! empty($book->gallery_images) && is_array($book->gallery_images)) {
                foreach ($book->gallery_images as $oldImage) {
                    if (! str_starts_with($oldImage, 'http') && ! str_starts_with($oldImage, 'images/')) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }
            }

            $galleryPaths = [];
            foreach ($request->file('gallery_images') as $file) {
                if ($file->isValid()) {
                    $galleryPaths[] = $file->store('books/gallery', 'public');
                }
            }
            if (! empty($galleryPaths)) {
                $validated['gallery_images'] = $galleryPaths;
            }
        }

        if ($request->hasFile('sample_file')) {
            if ($book->sample_file && ! str_starts_with($book->sample_file, 'http')) {
                Storage::disk('public')->delete($book->sample_file);
            }
            $path = $request->file('sample_file')->store('books/samples', 'public');
            $validated['sample_file'] = $path;
        }

        if ($request->hasFile('ebook_file')) {
            if ($book->ebook_file && ! str_starts_with($book->ebook_file, 'http')) {
                Storage::disk('public')->delete($book->ebook_file);
            }
            $file = $request->file('ebook_file');
            $path = $file->store('books/ebooks', 'public');
            $validated['ebook_file'] = $path;

            // Auto-fill file_size if empty
            if (empty($validated['file_size'])) {
                $bytes = $file->getSize();
                $validated['file_size'] = $bytes >= 1048576
                    ? number_format($bytes / 1048576, 1).' MB'
                    : number_format($bytes / 1024, 1).' KB';
            }
        }

        $book->update($validated);

        return redirect()->route('admin.books.index')->with('success', 'E-Book updated successfully.');
    }

    public function destroy(Book $book): RedirectResponse
    {
        if ($book->cover_image && ! str_starts_with($book->cover_image, 'http') && ! str_starts_with($book->cover_image, 'images/')) {
            Storage::disk('public')->delete($book->cover_image);
        }

        if (! empty($book->gallery_images) && is_array($book->gallery_images)) {
            foreach ($book->gallery_images as $oldImage) {
                if (! str_starts_with($oldImage, 'http') && ! str_starts_with($oldImage, 'images/')) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
        }

        if ($book->sample_file && ! str_starts_with($book->sample_file, 'http')) {
            Storage::disk('public')->delete($book->sample_file);
        }

        if ($book->ebook_file && ! str_starts_with($book->ebook_file, 'http')) {
            Storage::disk('public')->delete($book->ebook_file);
        }

        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'E-Book deleted successfully.');
    }

    public function toggleStatus(Book $book): RedirectResponse|JsonResponse
    {
        $newStatus = $book->status === 'active' ? 'inactive' : 'active';
        $book->update(['status' => $newStatus]);

        if (request()->expectsJson() || request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $book->status,
                'message' => "E-Book status updated to {$book->status}.",
            ]);
        }

        return back()->with('success', "E-Book status updated to {$book->status}.");
    }
}
