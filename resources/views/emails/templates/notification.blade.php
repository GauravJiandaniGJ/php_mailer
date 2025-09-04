<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #2196F3;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 5px 5px;
        }
        .alert {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            background-color: #2196F3;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title ?? 'Notification' }}</h1>
    </div>
    <div class="content">
        <p>Hello {{ $name ?? 'User' }},</p>
        
        <div class="alert">
            <strong>{{ $alert_title ?? 'Important Update' }}</strong><br>
            {{ $message ?? 'You have a new notification.' }}
        </div>
        
        @if(isset($details))
        <h3>Details:</h3>
        <p>{{ $details }}</p>
        @endif
        
        @if(isset($action_required) && $action_required)
        <p><strong>Action Required:</strong> Please review this notification and take necessary action.</p>
        @endif
        
        @if(isset($action_url))
        <a href="{{ $action_url }}" class="button">View Details</a>
        @endif
        
        <p>This notification was sent on {{ date('F j, Y \a\t g:i A') }}.</p>
        
        <p>Best regards,<br>The Team</p>
    </div>
</body>
</html>