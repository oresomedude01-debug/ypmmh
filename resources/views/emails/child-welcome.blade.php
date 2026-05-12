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
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
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
            font-size: 18px;
            margin-bottom: 20px;
            color: #333;
        }
        .credentials-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .credentials-box p {
            margin: 10px 0;
            font-size: 15px;
        }
        .credentials-box strong {
            color: #0B4D73;
            font-family: monospace;
            font-size: 16px;
            background: #fff;
            padding: 4px 8px;
            border-radius: 4px;
            border: 1px solid #cbd5e1;
            margin-left: 5px;
        }
        .btn {
            display: inline-block;
            background: #0B4D73;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .footer {
            background: #f8fafc;
            padding: 20px;
            text-align: center;
            font-size: 13px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
        a {
            color: #0B4D73;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div style="padding: 20px;">
        <div class="container">
            <div class="header">
                <h1>Welcome to YPMMH! 🎉</h1>
                <p style="margin: 10px 0 0 0; font-size: 16px; opacity: 0.9;">Your learning journey begins here</p>
            </div>

            <div class="content">
                <div class="greeting">
                    Hello {{ $child->first_name }},
                </div>

                <p>
                    Your account has just been successfully created by {{ $parent->first_name }} {{ $parent->last_name }}. 
                    You can now log in to the portal to view your assigned programs, complete lessons, and connect with your mentors.
                </p>

                <div class="credentials-box">
                    <h3 style="margin-top: 0; color: #0B4D73;">Your Login Details</h3>
                    <p>Email: <strong>{{ $child->email }}</strong></p>
                    <p>Temporary Password: <strong>password123</strong></p>
                </div>

                <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 0 4px 4px 0;">
                    <p style="margin: 0; color: #856404; font-size: 14px;">
                        <strong>Security Notice:</strong> For your safety, you will be prompted to change this temporary password the very first time you log in.
                    </p>
                </div>

                <div style="text-align: center;">
                    <a href="{{ $url }}" class="btn">Verify & Set Password</a>
                </div>
                <p style="font-size: 14px; color: #666;">
                    If the button doesn't work, you can copy and paste this link into your browser:<br>
                    <span style="word-break: break-all; color: #0B4D73;">{{ $url }}</span>
                </p>
            </div>

            <div class="footer">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                <p>If you didn't expect this email, please contact our support team.</p>
            </div>
        </div>
    </div>
</body>
</html>
