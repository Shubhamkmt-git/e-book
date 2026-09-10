<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminCategoryController extends Controller
{
    /** @return list<array{class:string,label:string}> */
    protected function getAvailableIcons(): array
    {
        return [
            ['class' => 'fa-solid fa-book',           'label' => 'Book'],
            ['class' => 'fa-solid fa-book-open',      'label' => 'Book Open'],
            ['class' => 'fa-solid fa-book-bookmark',  'label' => 'Book Bookmark'],
            ['class' => 'fa-solid fa-graduation-cap', 'label' => 'Education'],
            ['class' => 'fa-solid fa-flask',          'label' => 'Science'],
            ['class' => 'fa-solid fa-atom',           'label' => 'Physics'],
            ['class' => 'fa-solid fa-microscope',     'label' => 'Research'],
            ['class' => 'fa-solid fa-brain',          'label' => 'Psychology'],
            ['class' => 'fa-solid fa-heart-pulse',    'label' => 'Health'],
            ['class' => 'fa-solid fa-stethoscope',    'label' => 'Medicine'],
            ['class' => 'fa-solid fa-laptop-code',    'label' => 'Programming'],
            ['class' => 'fa-solid fa-code',           'label' => 'Code'],
            ['class' => 'fa-solid fa-database',       'label' => 'Database'],
            ['class' => 'fa-solid fa-robot',          'label' => 'AI & ML'],
            ['class' => 'fa-solid fa-chart-line',     'label' => 'Finance'],
            ['class' => 'fa-solid fa-coins',          'label' => 'Economics'],
            ['class' => 'fa-solid fa-briefcase',      'label' => 'Business'],
            ['class' => 'fa-solid fa-landmark',       'label' => 'History'],
            ['class' => 'fa-solid fa-globe',          'label' => 'Geography'],
            ['class' => 'fa-solid fa-palette',        'label' => 'Art & Design'],
            ['class' => 'fa-solid fa-music',          'label' => 'Music'],
            ['class' => 'fa-solid fa-camera',         'label' => 'Photography'],
            ['class' => 'fa-solid fa-film',           'label' => 'Film'],
            ['class' => 'fa-solid fa-gamepad',        'label' => 'Gaming'],
            ['class' => 'fa-solid fa-futbol',         'label' => 'Sports'],
            ['class' => 'fa-solid fa-dumbbell',       'label' => 'Fitness'],
            ['class' => 'fa-solid fa-leaf',           'label' => 'Nature'],
            ['class' => 'fa-solid fa-tree',           'label' => 'Environment'],
            ['class' => 'fa-solid fa-utensils',       'label' => 'Food & Cooking'],
            ['class' => 'fa-solid fa-plane',          'label' => 'Travel'],
            ['class' => 'fa-solid fa-rocket',         'label' => 'Space'],
            ['class' => 'fa-solid fa-shield-halved',  'label' => 'Security'],
            ['class' => 'fa-solid fa-scale-balanced', 'label' => 'Law'],
            ['class' => 'fa-solid fa-newspaper',      'label' => 'News'],
            ['class' => 'fa-solid fa-scroll',         'label' => 'Literature'],
            ['class' => 'fa-solid fa-pen-nib',        'label' => 'Writing'],
            ['class' => 'fa-solid fa-language',       'label' => 'Language'],
            ['class' => 'fa-solid fa-lightbulb',      'label' => 'Ideas'],
            ['class' => 'fa-solid fa-building',       'label' => 'Architecture'],
            ['class' => 'fa-solid fa-wrench',         'label' => 'Engineering'],
            ['class' => 'fa-solid fa-car',            'label' => 'Automotive'],
            ['class' => 'fa-solid fa-house',          'label' => 'Real Estate'],
            ['class' => 'fa-solid fa-spa',            'label' => 'Wellness'],
            ['class' => 'fa-solid fa-chess',          'label' => 'Strategy'],
            ['class' => 'fa-solid fa-star',           'label' => 'Featured'],
            ['class' => 'fa-solid fa-fire',           'label' => 'Popular'],
            ['class' => 'fa-solid fa-crown',          'label' => 'Premium'],
        ];
    }

    public function index(Request $request): View
    {
        $query = Category::query();
        if ($request->filled('search')) {
            $search = trim($request->string('search'));
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")->orWhere('slug', 'like', "%{$search}%");
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }
        if ($request->filled('featured')) {
            $query->where('is_featured', $request->string('featured') === '1');
        }
        $categories = $query->latest('id')->paginate(15)->withQueryString();

        return view('admin.categories.index', [
            'categories' => $categories,
            'totalCount' => Category::count(),
            'activeCount' => Category::where('status', 'active')->count(),
            'featuredCount' => Category::where('is_featured', true)->count(),
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.create', [
            'icons' => $this->getAvailableIcons(),
            'statuses' => ['active' => 'Active', 'inactive' => 'Inactive'],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        // Always auto-generate slug from title
        $request->merge(['slug' => Str::slug($request->string('title'))]);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:categories,slug'],
            'description' => ['nullable', 'string', 'max:1000'],
            'icon' => ['nullable', 'string', 'max:100'],
            'is_featured' => ['boolean'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function show(Category $category): View
    {
        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', [
            'category' => $category,
            'icons' => $this->getAvailableIcons(),
            'statuses' => ['active' => 'Active', 'inactive' => 'Inactive'],
        ]);
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        // Always auto-generate slug from title
        $request->merge(['slug' => Str::slug($request->string('title'))]);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('categories', 'slug')->ignore($category->id)],
            'description' => ['nullable', 'string', 'max:1000'],
            'icon' => ['nullable', 'string', 'max:100'],
            'is_featured' => ['boolean'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }

    public function toggleStatus(Category $category): RedirectResponse|JsonResponse
    {
        $category->update(['status' => $category->status === 'active' ? 'inactive' : 'active']);
        if (request()->expectsJson() || request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'status' => $category->status, 'message' => "Category status changed to {$category->status}."]);
        }

        return back()->with('success', "Category status changed to {$category->status}.");
    }
}
