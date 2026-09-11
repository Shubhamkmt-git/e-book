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
     * Show the single CTA manage page.
     * Always works on record ID=1 (upsert pattern).
     */
    public function manage(): View
    {
        $cta = Cta::firstOrCreate(
            ['id' => 1],
            [
                'label' => null,
                'title' => null,
                'subtitle' => null,
                'bg_image' => null,
                'status' => 'active',
                'sort_order' => 0,
            ]
        );

        return view('admin.ctas.manage', compact('cta'));
    }

    /**
     * Update the single CTA record.
     */
    public function update(Request $request): RedirectResponse
    {
        $cta = Cta::firstOrCreate(['id' => 1]);

        $request->validate([
            'label' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
            'bg_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:5120'],
        ]);

        if ($request->boolean('remove_bg_image')) {
            if ($cta->bg_image && Storage::disk('public')->exists($cta->bg_image)) {
                Storage::disk('public')->delete($cta->bg_image);
            }
            $cta->bg_image = null;
        } elseif ($request->hasFile('bg_image')) {
            if ($cta->bg_image && Storage::disk('public')->exists($cta->bg_image)) {
                Storage::disk('public')->delete($cta->bg_image);
            }
            $cta->bg_image = $request->file('bg_image')->store('ctas', 'public');
        }

        $cta->label = $request->label;
        $cta->title = $request->title;
        $cta->subtitle = $request->subtitle;
        $cta->status = $request->status;
        $cta->save();

        return redirect()
            ->route('admin.ctas.manage')
            ->with('success', 'CTA section updated successfully.');
    }
}
