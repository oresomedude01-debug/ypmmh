<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 0;
        }
        .header {
            background: linear-gradient(135deg, #0B4D73, #0D6FA0);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .content {
            padding: 30px 20px;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
            color: #333;
        }
        .greeting strong {
            color: #0B4D73;
        }
        .message {
            background: #f9f9f9;
            border-left: 4px solid #0B4D73;
            padding: 20px;
            margin: 20px 0;
            line-height: 1.8;
            color: #555;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .sender {
            margin: 20px 0;
            padding: 15px;
            background: #e3f2fd;
            border-radius: 8px;
            font-size: 14px;
            color: #0B4D73;
        }
        .sender strong {
            display: block;
            margin-bottom: 5px;
        }
        .footer {
            background: #f5f5f5;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #eee;
        }
        .badge {
            display: inline-block;
            background: #0B4D73;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
        }
        a {
            color: #0B4D73;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>{{ config('app.name', 'Our App') }}</h1>
            <p style="margin: 10px 0 0 0; font-size: 14px; opacity: 0.9;">Direct Message from Admin</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Greeting -->
            <div class="greeting">
                Hello <strong>{{ $recipientName }}</strong>,
            </div>

            <!-- Badge for recipient type -->
            <p style="margin: 0 0 20px 0; font-size: 14px; color: #666;">
                <span style="background: #f0f0f0; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                    {{ $recipientType }}
                </span>
            </p>

            <!-- Message -->
            <div class="message">{{ $body }}</div>

            <!-- Sender Info -->
            <div class="sender">
                <strong>Sent by:</strong>
                {{ $senderName ?? 'Admin' }}
                <br>
                <small style="opacity: 0.8;">{{ now()->format('l, F j, Y \a\t g:i A') }}</small>
            </div>

            <!-- Closing -->
            <p style="margin: 20px 0; color: #666; font-size: 14px;">
                If you have any questions or need further assistance, please don't hesitate to reach out.
            </p>

            <p style="margin: 10px 0; color: #666; font-size: 14px;">
                Best regards,<br>
                <strong>{{ config('app.name', 'Our Platform') }} Team</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0 0 10px 0;">
                This is an automated message sent from {{ config('app.name', 'Our App') }}.
                <br>
                © {{ date('Y') }} {{ config('app.name', 'Our Platform') }}. All rights reserved.
            </p>
            <p style="margin: 0; color: #bbb;">
                <a href="{{ url('/') }}" style="color: #0B4D73;">Visit our platform</a>
            </p>
        </div>
    </div>
</body>
</html>
