<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\LegalPage;
use Illuminate\View\View;

class LegalPageController extends Controller
{
    /**
     * Show the Privacy Policy page.
     */
    public function privacyPolicy(): View
    {
        $page = LegalPage::getBySlug('privacy-policy');

        if ($page->status !== 'active') {
            abort(404, 'Privacy Policy is currently unavailable.');
        }

        return view('frontend.pages.legal', compact('page'));
    }

    /**
     * Show the Terms of Service page.
     */
    public function termsOfService(): View
    {
        $page = LegalPage::getBySlug('terms-of-service');

        if ($page->status !== 'active') {
            abort(404, 'Terms of Service is currently unavailable.');
        }

        return view('frontend.pages.legal', compact('page'));
    }
}
