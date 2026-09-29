<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Newsletter Subscriber</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2 style="color: #2c3e50; border-bottom: 2px solid #eee; padding-bottom: 10px;">New Newsletter Subscription</h2>
        <p>Hello Admin,</p>
        <p>A new user has subscribed to the newsletter:</p>
        <p style="font-size: 16px; background-color: #eef2f7; padding: 12px; border-radius: 4px;">
            <strong>Subscriber Email:</strong> {{ $subscriberEmail }}
        </p>
        <p>This subscriber has been saved to your admin database records.</p>
    </div>
</body>
</html>
