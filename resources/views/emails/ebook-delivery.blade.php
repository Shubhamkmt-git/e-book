<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $appSetting->app_name ?? 'E-Book' }} - Your Digital Delivery</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            color: #334155;
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
            display: block;
        }
    </style>
</head>
<body style="margin: 0; padding: 30px 15px; background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 580px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);">
        
        <!-- Header / Dynamic App Logo & Brand -->
        <tr>
            <td style="padding: 28px 32px 20px 32px; border-bottom: 1px solid #f1f5f9;">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td align="left" style="vertical-align: middle;">
                            @if (!empty($appSetting?->logo_light_url) || !empty($appSetting?->logo_dark_url))
                                <img 
                                    src="{{ $appSetting->logo_light_url ?? $appSetting->logo_dark_url }}" 
                                    alt="{{ $appSetting->app_name ?? 'Logo' }}" 
                                    style="max-height: 38px; max-width: 170px; object-contain: contain; height: auto;" 
                                    border="0"
                                >
                            @else
                                <span style="font-size: 20px; font-weight: 800; color: #0f172a; letter-spacing: -0.5px; text-transform: uppercase;">
                                    {{ $appSetting->app_name ?? 'E-Book' }}<span style="color: #7c3aed;">.</span>
                                </span>
                            @endif
                        </td>
                        <td align="right" style="vertical-align: middle;">
                            <span style="display: inline-block; font-size: 11px; font-weight: 700; color: #059669; background-color: #ecfdf5; border: 1px solid #a7f3d0; padding: 4px 10px; border-radius: 9999px; text-transform: uppercase; letter-spacing: 0.5px;">
                                Paid &amp; Delivered
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- Main Body -->
        <tr>
            <td style="padding: 32px 32px 24px 32px;">
                <p style="margin: 0 0 16px 0; font-size: 17px; font-weight: 700; color: #0f172a;">
                    Hello {{ $customer?->name ?? 'Reader' }},
                </p>
                <p style="margin: 0 0 24px 0; font-size: 14px; line-height: 1.6; color: #475569;">
                    Thank you for your purchase from <strong>{{ $appSetting->app_name ?? 'our digital library' }}</strong>. Your payment was verified successfully and your DRM-free publication is ready.
                </p>

                <!-- Book Highlight Card -->
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 24px; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;">
                    <tr>
                        <td style="padding: 18px 20px;">
                            <span style="display: block; font-size: 10px; font-weight: 700; color: #7c3aed; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 4px;">
                                Digital E-Book • DRM-Free
                            </span>
                            <h2 style="margin: 0 0 4px 0; font-size: 16px; font-weight: 700; color: #0f172a; line-height: 1.4;">
                                {{ $book?->title ?? $purchase->book_title }}
                            </h2>
                            <p style="margin: 0; font-size: 13px; color: #64748b;">
                                By {{ $book?->author_name ?? 'Featured Author' }}
                            </p>
                        </td>
                    </tr>
                </table>

                <!-- Attached PDF Notice Box -->
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 28px; background-color: #faf5ff; border: 1px solid #e9d5ff; border-radius: 12px;">
                    <tr>
                        <td style="padding: 18px 20px;">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td width="36" style="vertical-align: top; font-size: 22px; line-height: 1;">
                                        📎
                                    </td>
                                    <td style="vertical-align: top;">
                                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 700; color: #581c87;">
                                            PDF Attached to this Email
                                        </p>
                                        <p style="margin: 0; font-size: 12px; line-height: 1.5; color: #6b21a8;">
                                            The complete PDF file is attached below. You can download and read it anytime on any phone, tablet, e-reader, or computer.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <!-- Transaction Summary Table -->
                <p style="margin: 0 0 10px 0; font-size: 12px; font-weight: 700; color: #0f172a; text-transform: uppercase; letter-spacing: 0.8px;">
                    Order Receipt
                </p>
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; margin-bottom: 24px; font-size: 13px; border: 1px solid #f1f5f9; border-radius: 8px; overflow: hidden;">
                    <tr style="background-color: #f8fafc;">
                        <td style="padding: 10px 14px; color: #64748b; font-weight: 500; border-bottom: 1px solid #f1f5f9;" width="40%">Order ID</td>
                        <td style="padding: 10px 14px; color: #0f172a; font-weight: 600; font-family: monospace; border-bottom: 1px solid #f1f5f9;" width="60%">#{{ $purchase->transaction_id }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 14px; color: #64748b; font-weight: 500; border-bottom: 1px solid #f1f5f9;">Date</td>
                        <td style="padding: 10px 14px; color: #0f172a; font-weight: 500; border-bottom: 1px solid #f1f5f9;">{{ $purchase->updated_at ? $purchase->updated_at->format('M d, Y h:i A') : now()->format('M d, Y') }}</td>
                    </tr>
                    <tr style="background-color: #f8fafc;">
                        <td style="padding: 10px 14px; color: #64748b; font-weight: 500; border-bottom: 1px solid #f1f5f9;">Payment Method</td>
                        <td style="padding: 10px 14px; color: #0f172a; font-weight: 600; border-bottom: 1px solid #f1f5f9;">{{ ucfirst($purchase->payment_method ?? 'Online Payment') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 14px; color: #64748b; font-weight: 500;">Amount Paid</td>
                        <td style="padding: 10px 14px; color: #059669; font-weight: 700; font-size: 14px;">₹{{ number_format((float)$purchase->amount, 2) }}</td>
                    </tr>
                </table>

                <!-- Support Info -->
                <p style="margin: 0; font-size: 12px; line-height: 1.6; color: #94a3b8;">
                    Questions or need help? 
                    @if (!empty($appSetting?->contact_email))
                        Reach us anytime at <a href="mailto:{{ $appSetting->contact_email }}" style="color: #7c3aed; text-decoration: none; font-weight: 500;">{{ $appSetting->contact_email }}</a>.
                    @else
                        Reply directly to this email and our team will be glad to assist you.
                    @endif
                </p>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="padding: 20px 32px; background-color: #f8fafc; border-top: 1px solid #f1f5f9; text-align: center;">
                <p style="margin: 0 0 4px 0; font-size: 11px; color: #94a3b8; line-height: 1.5;">
                    &copy; {{ date('Y') }} <strong>{{ $appSetting->app_name ?? config('app.name', 'E-Book') }}</strong>. All rights reserved.
                </p>
                <p style="margin: 0; font-size: 10px; color: #cbd5e1;">
                    This is an automated delivery email for your digital transaction.
                </p>
            </td>
        </tr>

    </table>
</body>
</html>
