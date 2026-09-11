<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LegalPage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LegalPageController extends Controller
{
    /**
     * Display a listing of all legal and policy pages.
     */
    public function index(): View
    {
        // Ensure default records exist
        LegalPage::getBySlug('privacy-policy');
        LegalPage::getBySlug('terms-of-service');

        $pages = LegalPage::orderBy('id')->get();

        return view('admin.legal-pages.index', compact('pages'));
    }

    /**
     * Show the form for editing the specified legal page.
     */
    public function edit(string $slug): View
    {
        $page = LegalPage::getBySlug($slug);

        return view('admin.legal-pages.edit', compact('page'));
    }

    /**
     * Update the specified legal page.
     */
    public function update(Request $request, string $slug): RedirectResponse
    {
        $page = LegalPage::getBySlug($slug);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'last_updated_date' => ['nullable', 'date'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $page->update([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?: null,
            'content' => $validated['content'],
            'last_updated_date' => $validated['last_updated_date'] ?: now()->toDateString(),
            'meta_title' => $validated['meta_title'] ?: null,
            'meta_description' => $validated['meta_description'] ?: null,
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.legal-pages.index')
            ->with('success', "'{$page->title}' updated successfully.");
    }

    /**
     * Toggle status of the legal page.
     */
    public function toggleStatus(Request $request, string $slug): JsonResponse|RedirectResponse
    {
        $page = LegalPage::getBySlug($slug);
        $newStatus = $page->status === 'active' ? 'inactive' : 'active';
        $page->update(['status' => $newStatus]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $newStatus,
                'message' => "'{$page->title}' is now ".ucfirst($newStatus).'.',
            ]);
        }

        return redirect()->back()
            ->with('success', "'{$page->title}' is now ".ucfirst($newStatus).'.');
    }
}
