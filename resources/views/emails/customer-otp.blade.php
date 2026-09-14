<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $appSetting->app_name ?? 'E-Book' }} - Email Verification Code</title>
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
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 540px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);">
        
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
                                    style="max-height: 36px; max-width: 160px; object-contain: contain; height: auto;" 
                                    border="0"
                                >
                            @else
                                <span style="font-size: 20px; font-weight: 800; color: #0f172a; letter-spacing: -0.5px; text-transform: uppercase;">
                                    {{ $appSetting->app_name ?? 'E-Book' }}<span style="color: #7c3aed;">.</span>
                                </span>
                            @endif
                        </td>
                        <td align="right" style="vertical-align: middle;">
                            <span style="display: inline-block; font-size: 11px; font-weight: 700; color: #7c3aed; background-color: #f5f3ff; border: 1px solid #ddd6fe; padding: 4px 10px; border-radius: 9999px; text-transform: uppercase; letter-spacing: 0.5px;">
                                Verification Code
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- Main Body -->
        <tr>
            <td style="padding: 32px 32px 24px 32px;">
                <p style="margin: 0 0 12px 0; font-size: 17px; font-weight: 700; color: #0f172a;">
                    Hello{{ !empty($customerName) ? ' ' . e($customerName) : '' }},
                </p>
                <p style="margin: 0 0 24px 0; font-size: 14px; line-height: 1.6; color: #475569;">
                    Use the following 6-digit one-time password (OTP) to complete your registration and verify your email address on <strong>{{ $appSetting->app_name ?? 'our digital library' }}</strong>.
                </p>

                <!-- OTP Display Box -->
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 24px; background-color: #faf5ff; border: 1px solid #e9d5ff; border-radius: 14px; text-align: center;">
                    <tr>
                        <td style="padding: 24px 20px; text-align: center;">
                            <span style="display: block; font-size: 11px; font-weight: 700; color: #6b21a8; text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 8px;">
                                Your One-Time Code
                            </span>
                            <div style="font-size: 34px; font-weight: 800; letter-spacing: 8px; color: #581c87; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; line-height: 1.2;">
                                {{ $otpCode }}
                            </div>
                            <span style="display: inline-block; font-size: 12px; color: #7c3aed; margin-top: 10px; font-weight: 500;">
                                ⏱ Valid for 10 minutes
                            </span>
                        </td>
                    </tr>
                </table>

                <p style="margin: 0 0 16px 0; font-size: 13px; line-height: 1.6; color: #64748b;">
                    If you did not request this verification code, you can safely ignore this email. Someone may have entered your email address by mistake.
                </p>

                <!-- Support Info -->
                <p style="margin: 0; font-size: 12px; line-height: 1.6; color: #94a3b8;">
                    Need assistance?
                    @if (!empty($appSetting?->contact_email))
                        Reach us at <a href="mailto:{{ $appSetting->contact_email }}" style="color: #7c3aed; text-decoration: none; font-weight: 500;">{{ $appSetting->contact_email }}</a>.
                    @else
                        Reply directly to this email and we will be happy to help.
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
                    This is an automated security verification message.
                </p>
            </td>
        </tr>

    </table>
</body>
</html>
