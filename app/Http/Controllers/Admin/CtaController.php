<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CtaController extends Controller
{
    /**
     * Display a listing of CTAs.
     */
    public function index(Request $request): View
    {
        $query = Cta::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('subtitle', 'like', "%{$search}%")
                    ->orWhere('label', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $ctas = $query->orderBy('sort_order')->orderByDesc('created_at')->paginate(15)->withQueryString();
        $totalCount = Cta::count();
        $activeCount = Cta::where('status', 'active')->count();
        $statuses = Cta::statuses();

        return view('admin.ctas.index', compact('ctas', 'totalCount', 'activeCount', 'statuses'));
    }

    /**
     * Show the form for creating a new CTA.
     */
    public function create(): View
    {
        $statuses = Cta::statuses();

        return view('admin.ctas.create', compact('statuses'));
    }

    /**
     * Store a newly created CTA in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'label' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'bg_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:5120'],
        ]);

        if ($request->hasFile('bg_image')) {
            $validated['bg_image'] = $request->file('bg_image')->store('ctas', 'public');
        }

        Cta::create([
            'label' => $validated['label'] ?? null,
            'title' => $validated['title'] ?? null,
            'subtitle' => $validated['subtitle'] ?? null,
            'status' => $validated['status'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'bg_image' => $validated['bg_image'] ?? null,
        ]);

        return redirect()->route('admin.ctas.index')
            ->with('success', 'CTA created successfully.');
    }

    /**
     * Show the form for editing the specified CTA.
     */
    public function edit(Cta $cta): View
    {
        $statuses = Cta::statuses();

        return view('admin.ctas.edit', compact('cta', 'statuses'));
    }

    /**
     * Update the specified CTA in storage.
     */
    public function update(Request $request, Cta $cta): RedirectResponse
    {
        $validated = $request->validate([
            'label' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'bg_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:5120'],
        ]);

        if ($request->hasFile('bg_image')) {
            // Delete old image
            if ($cta->bg_image && Storage::disk('public')->exists($cta->bg_image)) {
                Storage::disk('public')->delete($cta->bg_image);
            }
            $validated['bg_image'] = $request->file('bg_image')->store('ctas', 'public');
        } else {
            $validated['bg_image'] = $cta->bg_image; // Keep existing if not updated
        }

        $cta->update([
            'label' => $validated['label'] ?? null,
            'title' => $validated['title'] ?? null,
            'subtitle' => $validated['subtitle'] ?? null,
            'status' => $validated['status'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'bg_image' => $validated['bg_image'],
        ]);

        return redirect()->route('admin.ctas.index')
            ->with('success', 'CTA updated successfully.');
    }

    /**
     * Toggle the active status of a CTA.
     */
    public function toggleStatus(Cta $cta): RedirectResponse
    {
        $cta->update([
            'status' => $cta->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', 'CTA status updated.');
    }

    /**
     * Remove the specified CTA from storage.
     */
    public function destroy(Cta $cta): RedirectResponse
    {
        if ($cta->bg_image && Storage::disk('public')->exists($cta->bg_image)) {
            Storage::disk('public')->delete($cta->bg_image);
        }

        $cta->delete();

        return redirect()->route('admin.ctas.index')
            ->with('success', 'CTA deleted successfully.');
    }
}
