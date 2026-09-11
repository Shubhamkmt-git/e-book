<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your E-Book Delivery</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        .wrapper {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #6b46c1 0%, #7a58a9 50%, #4c1d95 100%);
            padding: 35px 30px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .header p {
            margin: 8px 0 0 0;
            font-size: 14px;
            color: #e9d5ff;
        }
        .content {
            padding: 35px 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 12px;
        }
        .intro-text {
            font-size: 14px;
            line-height: 1.6;
            color: #475569;
            margin-bottom: 25px;
        }
        .book-card {
            background: #faf5ff;
            border: 1px solid #e9d5ff;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .book-info h3 {
            margin: 0 0 4px 0;
            font-size: 16px;
            font-weight: 700;
            color: #4c1d95;
        }
        .book-info p {
            margin: 0 0 6px 0;
            font-size: 13px;
            color: #64748b;
        }
        .badge {
            display: inline-block;
            background-color: #7a58a9;
            color: #ffffff;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 3px 8px;
            border-radius: 9999px;
        }
        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 13px;
        }
        .receipt-table th {
            text-align: left;
            padding: 10px 12px;
            background: #f1f5f9;
            color: #475569;
            font-weight: 600;
            border-bottom: 1px solid #e2e8f0;
        }
        .receipt-table td {
            padding: 12px;
            border-bottom: 1px solid #f1f5f9;
            color: #1e293b;
        }
        .btn-container {
            text-align: center;
            margin: 30px 0 20px 0;
        }
        .btn-download {
            display: inline-block;
            background: linear-gradient(135deg, #7a58a9 0%, #6b46c1 100%);
            color: #ffffff !important;
            text-decoration: none;
            font-size: 15px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 14px 34px;
            border-radius: 9999px;
            box-shadow: 0 4px 14px rgba(122, 88, 169, 0.4);
        }
        .footer {
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 24px 30px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            line-height: 1.5;
        }
        .footer a {
            color: #7a58a9;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Header -->
        <div class="header">
            <h1>Payment Confirmed</h1>
            <p>Your digital e-book is ready for instant reading</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">Hello, {{ $customer?->name ?? 'Reader' }}!</div>
            <p class="intro-text">
                Thank you for your purchase. Your payment {{ $purchase->payment_method ? 'via '.ucfirst($purchase->payment_method) : '' }} has been verified successfully. Your DRM-Free e-book edition is attached to this email and also available for instant download below.
            </p>

            <!-- Book Showcase Box -->
            <div class="book-card">
                <div class="book-info">
                    <span class="badge">DRM-Free Edition</span>
                    <h3 style="margin-top: 6px;">{{ $book?->title ?? $purchase->book_title }}</h3>
                    <p>By {{ $book?->author_name ?? 'Featured Author' }} • {{ $book?->format ?? 'EPUB & PDF' }}</p>
                </div>
            </div>

            <!-- Download Button -->
            <div class="btn-container">
                <a href="{{ $downloadUrl }}" class="btn-download" target="_blank">
                    Download E-Book (PDF)
                </a>
            </div>
            <p style="text-align: center; font-size: 12px; color: #64748b; margin-top: 5px;">
                (If an official PDF was attached, you can also open it directly from this email)
            </p>

            <!-- Transaction Details Table -->
            <h4 style="margin: 28px 0 10px 0; font-size: 14px; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px;">
                Transaction Receipt
            </h4>
            <table class="receipt-table">
                <tr>
                    <th>Item Description</th>
                    <td style="font-weight: 600;">{{ $purchase->book_title }}</td>
                </tr>
                <tr>
                    <th>Transaction ID</th>
                    <td style="font-family: monospace; color: #64748b;">{{ $purchase->transaction_id }}</td>
                </tr>
                <tr>
                    <th>Amount Paid</th>
                    <td style="font-weight: 700; color: #059669;">₹{{ number_format((float)$purchase->amount, 2) }}</td>
                </tr>
                <tr>
                    <th>Date & Time</th>
                    <td>{{ $purchase->updated_at ? $purchase->updated_at->format('M d, Y h:i A') : now()->format('M d, Y') }}</td>
                </tr>
                <tr>
                    <th>Payment Status</th>
                    <td><span style="color: #059669; font-weight: 700; text-transform: uppercase;">● Completed</span></td>
                </tr>
            </table>

            <p style="font-size: 13px; color: #64748b; line-height: 1.6; margin-top: 20px;">
                Need help or have questions about your publication? Simply reply directly to this email or visit our help center.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'E-Book Store') }}. All rights reserved.</p>
            <p>You received this email because you completed a verified digital purchase.</p>
        </div>
    </div>
</body>
</html>
