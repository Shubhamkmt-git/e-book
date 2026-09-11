<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegalPage extends Model
{
    use HasFactory;

    /** @var list<string> */
    protected $fillable = [
        'slug',
        'title',
        'subtitle',
        'content',
        'last_updated_date',
        'meta_title',
        'meta_description',
        'status',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'last_updated_date' => 'date',
    ];

    /**
     * Get or create a default legal page by slug.
     */
    public static function getBySlug(string $slug): self
    {
        $page = self::where('slug', $slug)->first();

        if ($page) {
            return $page;
        }

        if ($slug === 'privacy-policy') {
            return self::create([
                'slug' => 'privacy-policy',
                'title' => 'Privacy Policy',
                'subtitle' => 'How we collect, protect, and handle your personal data and digital purchases.',
                'last_updated_date' => now()->toDateString(),
                'status' => 'active',
                'meta_title' => 'Privacy Policy - DRM-Free Digital Reading Platform',
                'meta_description' => 'Learn how our e-book platform safeguards your customer data, purchase history, and privacy.',
                'content' => <<<'HTML'
<h2>1. Information We Collect</h2>
<p>When you browse our catalogue or purchase an e-book on our platform, we collect information necessary to complete your order and deliver your digital assets:</p>
<ul>
    <li><strong>Account & Contact Information:</strong> Your full name, email address, and optional phone number.</li>
    <li><strong>Transaction Details:</strong> Payment transaction identifiers, purchase timestamps, order amounts, and digital product identifiers. We do not store raw credit card or banking credentials on our servers.</li>
    <li><strong>Technical & Device Data:</strong> IP address, browser type, and device information used solely for security verification and download link authorization.</li>
</ul>

<h2>2. How We Use Your Information</h2>
<p>We use your personal data exclusively for the following operational purposes:</p>
<ul>
    <li>Processing digital e-book purchases and generating personalized, secure download links.</li>
    <li>Automating the instant delivery of purchased publications directly to your registered email address.</li>
    <li>Providing order history, re-download capabilities, and customer support.</li>
    <li>Sending critical service notifications regarding your orders and platform security.</li>
</ul>

<h2>3. Payment Processing & Security</h2>
<p>All financial transactions are handled securely through encrypted payment gateways (such as Easebuzz) using industry-standard 256-Bit SSL encryption. We never hold or store sensitive payment card details on our application servers.</p>

<h2>4. DRM-Free Digital Delivery</h2>
<p>Our publications are delivered in DRM-free formats (such as PDF and EPUB). Once purchased, you receive an automated email containing your download access and your account maintains lifetime access to re-download files.</p>

<h2>5. Data Retention & Your Rights</h2>
<p>You have the right to access your stored personal information, request corrections, or request account removal. If you wish to delete your customer records or have privacy questions, please contact our support team at support@example.com.</p>
HTML
            ]);
        }

        return self::create([
            'slug' => 'terms-of-service',
            'title' => 'Terms of Service',
            'subtitle' => 'Guidelines, rules, and licensing terms governing the use of our digital publication platform.',
            'last_updated_date' => now()->toDateString(),
            'status' => 'active',
            'meta_title' => 'Terms of Service - Digital E-Book Library',
            'meta_description' => 'Review the terms and conditions for purchasing, reading, and licensing digital e-books on our platform.',
            'content' => <<<'HTML'
<h2>1. Acceptance of Terms</h2>
<p>By accessing our website, creating an account, or purchasing any e-book from our platform, you agree to be bound by these Terms of Service, all applicable laws, and regulations. If you do not agree with any of these terms, you are prohibited from using or accessing this site.</p>

<h2>2. Digital Product License & DRM-Free Usage</h2>
<p>When you purchase an e-book from our platform, you are granted a non-exclusive, non-transferable, personal license to download and read the publication on your personal devices (e.g., Kindle, tablet, computer, mobile phone):</p>
<ul>
    <li>You may read the publication across multiple personal devices.</li>
    <li>You may not redistribute, resell, re-license, rent, or publicly broadcast the digital files without prior written consent from the author or publisher.</li>
    <li>You may not remove any copyright notices or proprietary markings embedded in the publication.</li>
</ul>

<h2>3. Pricing, Orders & Payment</h2>
<p>All prices listed on our platform are in Indian Rupees (INR) and are subject to change without prior notice. Once an order is completed via our secure payment gateway, an order confirmation and digital invoice will be issued to your registered email.</p>

<h2>4. Instant Delivery Guarantee</h2>
<p>Upon successful payment confirmation by the payment gateway, your e-book is made available immediately for on-screen download, and an automated delivery email with your digital files is dispatched to your registered email address within seconds.</p>

<h2>5. Refund & Cancellation Policy</h2>
<p>Due to the instant and irrevocable nature of digital goods, e-book purchases are generally non-refundable once the digital file has been downloaded or emailed. However, if you experience technical issues downloading the file or receive a corrupted file, our support team will promptly assist or re-issue your download access.</p>

<h2>6. User Accounts & Security</h2>
<p>You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account. Notify us immediately if you suspect unauthorized access to your customer account.</p>

<h2>7. Modifications to Terms</h2>
<p>We reserve the right to revise these terms of service at any time without prior notice. By continuing to use this website, you are agreeing to be bound by the then-current version of these Terms of Service.</p>
HTML
        ]);
    }
}
