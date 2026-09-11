<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Spotlight;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SpotlightController extends Controller
{
    /**
     * Show the Book of the Week / Spotlight management page.
     */
    public function manage(): View
    {
        $defaultBook = Book::where('status', 'active')->first();

        $spotlight = Spotlight::firstOrCreate(
            ['id' => 1],
            [
                'book_id' => $defaultBook?->id,
                'badge_text' => 'Book of the Week',
                'custom_title' => null,
                'custom_description' => null,
                'feature_tags' => ['Instant Download', 'Lifetime Access'],
                'button_text' => 'Buy Now',
                'status' => 'active',
            ]
        );

        if (! $spotlight->book_id && $defaultBook) {
            $spotlight->update(['book_id' => $defaultBook->id]);
        }

        $books = Book::with('category')
            ->where('status', 'active')
            ->orderBy('title')
            ->get();

        return view('admin.spotlight.manage', compact('spotlight', 'books'));
    }

    /**
     * Update the Book of the Week / Spotlight settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $spotlight = Spotlight::firstOrCreate(['id' => 1]);

        $validated = $request->validate([
            'book_id' => ['required', 'exists:books,id'],
            'badge_text' => ['nullable', 'string', 'max:255'],
            'custom_title' => ['nullable', 'string', 'max:255'],
            'custom_description' => ['nullable', 'string'],
            'feature_tags' => ['nullable', 'string'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $tags = [];
        if (! empty($validated['feature_tags'])) {
            $rawTags = preg_split('/[,\n]+/', $validated['feature_tags']);
            if (is_array($rawTags)) {
                $tags = array_values(array_filter(array_map('trim', $rawTags)));
            }
        }

        $spotlight->update([
            'book_id' => $validated['book_id'],
            'badge_text' => $validated['badge_text'] ?: 'Book of the Week',
            'custom_title' => $validated['custom_title'] ?: null,
            'custom_description' => $validated['custom_description'] ?: null,
            'feature_tags' => ! empty($tags) ? $tags : ['Instant Download', 'Lifetime Access'],
            'button_text' => $validated['button_text'] ?: 'Buy Now',
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.spotlight.manage')
            ->with('success', 'Book of the Week section updated successfully.');
    }

    /**
     * Toggle the active status of Book of the Week section.
     */
    public function toggleStatus(Request $request): JsonResponse|RedirectResponse
    {
        $spotlight = Spotlight::firstOrCreate(['id' => 1]);
        $newStatus = $spotlight->status === 'active' ? 'inactive' : 'active';
        $spotlight->update(['status' => $newStatus]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $newStatus,
                'message' => 'Spotlight section is now '.ucfirst($newStatus).'.',
            ]);
        }

        return redirect()->back()
            ->with('success', 'Spotlight section is now '.ucfirst($newStatus).'.');
    }
}
