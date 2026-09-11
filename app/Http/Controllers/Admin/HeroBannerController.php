<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroBanner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HeroBannerController extends Controller
{
    /**
     * Display a listing of hero banners.
     */
    public function index(Request $request): View
    {
        $query = HeroBanner::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('title_l2', 'like', "%{$search}%");
            });
        }

        $banners = $query->orderBy('sort_order')->orderByDesc('created_at')->paginate(15)->withQueryString();
        $totalCount = HeroBanner::count();

        return view('admin.hero-banners.index', compact('banners', 'totalCount'));
    }

    /**
     * Show the form for creating a new hero banner.
     */
    public function create(): View
    {
        return view('admin.hero-banners.create');
    }

    /**
     * Store a newly created hero banner.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'title_l2' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'banner_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:5120'],
            'primary_button' => ['nullable', 'string', 'max:100'],
            'primary_button_link' => ['nullable', 'string', 'max:500'],
            'secondary_button' => ['nullable', 'string', 'max:100'],
            'secondary_button_link' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $imagePath = null;
        if ($request->hasFile('banner_image')) {
            $imagePath = $request->file('banner_image')->store('hero-banners', 'public');
        }

        HeroBanner::create([
            'title' => $validated['title'],
            'title_l2' => $validated['title_l2'] ?? null,
            'description' => $validated['description'] ?? null,
            'banner_image' => $imagePath,
            'primary_button' => $validated['primary_button'] ?? null,
            'primary_button_link' => $validated['primary_button_link'] ?? null,
            'secondary_button' => $validated['secondary_button'] ?? null,
            'secondary_button_link' => $validated['secondary_button_link'] ?? null,
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.hero-banners.index')
            ->with('success', 'Hero banner created successfully.');
    }

    /**
     * Display the specified hero banner.
     */
    public function show(HeroBanner $heroBanner): View
    {
        return view('admin.hero-banners.show', compact('heroBanner'));
    }

    /**
     * Show the form for editing the specified hero banner.
     */
    public function edit(HeroBanner $heroBanner): View
    {
        return view('admin.hero-banners.edit', compact('heroBanner'));
    }

    /**
     * Update the specified hero banner.
     */
    public function update(Request $request, HeroBanner $heroBanner): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'title_l2' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'banner_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:5120'],
            'primary_button' => ['nullable', 'string', 'max:100'],
            'primary_button_link' => ['nullable', 'string', 'max:500'],
            'secondary_button' => ['nullable', 'string', 'max:100'],
            'secondary_button_link' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($request->hasFile('banner_image')) {
            // Delete old image
            if ($heroBanner->banner_image) {
                Storage::disk('public')->delete($heroBanner->banner_image);
            }
            $heroBanner->banner_image = $request->file('banner_image')->store('hero-banners', 'public');
        }

        $heroBanner->title = $validated['title'];
        $heroBanner->title_l2 = $validated['title_l2'] ?? null;
        $heroBanner->description = $validated['description'] ?? null;
        $heroBanner->primary_button = $validated['primary_button'] ?? null;
        $heroBanner->primary_button_link = $validated['primary_button_link'] ?? null;
        $heroBanner->secondary_button = $validated['secondary_button'] ?? null;
        $heroBanner->secondary_button_link = $validated['secondary_button_link'] ?? null;
        $heroBanner->is_active = $request->boolean('is_active', true);
        $heroBanner->sort_order = $validated['sort_order'] ?? 0;

        $heroBanner->save();

        return redirect()->route('admin.hero-banners.index')
            ->with('success', 'Hero banner updated successfully.');
    }

    /**
     * Toggle the active status of a hero banner.
     */
    public function toggleStatus(HeroBanner $heroBanner): RedirectResponse
    {
        $heroBanner->update(['is_active' => ! $heroBanner->is_active]);

        return back()->with('success', 'Banner status updated.');
    }

    /**
     * Remove the specified hero banner.
     */
    public function destroy(HeroBanner $heroBanner): RedirectResponse
    {
        if ($heroBanner->banner_image) {
            Storage::disk('public')->delete($heroBanner->banner_image);
        }

        $heroBanner->delete();

        return redirect()->route('admin.hero-banners.index')
            ->with('success', 'Hero banner deleted successfully.');
    }
}
