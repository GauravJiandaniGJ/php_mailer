# Laravel Mailer API

A Laravel-based API for sending emails using PHPMailer with customizable templates.

## Features

- Send emails using PHPMailer
- Template-based email system
- Custom HTML content support
- Multiple recipient support
- RESTful API endpoints

## Setup

1. **Install dependencies:**
   ```bash
   composer install
   ```

2. **Configure environment variables:**
   Copy `.env.example` to `.env` and update the mail configuration:
   ```env
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=your-email@gmail.com
   MAIL_PASSWORD=your-app-password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS="your-email@gmail.com"
   MAIL_FROM_NAME="Mailer API"
   ```

3. **Run migrations:**
   ```bash
   php artisan migrate
   ```

4. **Start the server:**
   ```bash
   php artisan serve
   ```

## API Endpoint

### POST /api/send-email

Send an email using either a template or custom HTML content.

**Request Body:**
```json
{
    "to": ["recipient1@example.com", "recipient2@example.com"],
    "from": "sender@example.com",
    "subject": "Email Subject",
    "template_id": "welcome", // Optional: Use predefined template
    "html_content": "<h1>Custom HTML</h1>", // Optional: Custom HTML content
    "data": { // Optional: Data for template variables
        "name": "John Doe",
        "company": "Example Corp"
    }
}
```

**Response:**
```json
{
    "success": true,
    "message": "Email sent successfully",
    "data": {
        "sent": true,
        "recipients": ["recipient@example.com"],
        "subject": "Email Subject",
        "timestamp": "2024-01-01T10:00:00"
    }
}
```

## Available Templates

### 1. Welcome Template (`welcome`)
Variables:
- `name` - Recipient name
- `company` - Company name
- `action_url` - Call-to-action URL

### 2. Notification Template (`notification`)
Variables:
- `name` - Recipient name
- `title` - Notification title
- `alert_title` - Alert section title
- `message` - Main message
- `details` - Additional details
- `action_required` - Boolean for action requirement
- `action_url` - Action URL

### 3. Invoice Template (`invoice`)
Variables:
- `customer_name` - Customer name
- `invoice_number` - Invoice number
- `invoice_date` - Invoice date
- `due_date` - Due date
- `purchase_order` - PO number
- `items` - Array of items with name, quantity, rate
- `total_amount` - Total amount
- `payment_instructions` - Payment instructions
- `payment_url` - Payment URL
- `company_name` - Company name

## Example Usage

### Using Template:
```bash
curl -X POST http://localhost:8000/api/send-email \
  -H "Content-Type: application/json" \
  -d '{
    "to": ["user@example.com"],
    "from": "noreply@example.com",
    "subject": "Welcome!",
    "template_id": "welcome",
    "data": {
      "name": "John Doe",
      "company": "Example Corp",
      "action_url": "https://example.com/get-started"
    }
  }'
```

### Using Custom HTML:
```bash
curl -X POST http://localhost:8000/api/send-email \
  -H "Content-Type: application/json" \
  -d '{
    "to": ["user@example.com"],
    "from": "noreply@example.com",
    "subject": "Custom Email",
    "html_content": "<h1>Hello World!</h1><p>This is a custom email.</p>"
  }'
```

## Email Configuration

For Gmail SMTP, you'll need to:
1. Enable 2-factor authentication
2. Generate an App Password
3. Use the App Password in `MAIL_PASSWORD`

For other email providers, update the SMTP settings accordingly.

## File Structure

```
app/
├── Http/Controllers/EmailController.php    # API controller
├── Services/EmailService.php               # Email service with PHPMailer
resources/views/emails/templates/
├── welcome.blade.php                       # Welcome email template
├── notification.blade.php                  # Notification email template
└── invoice.blade.php                       # Invoice email template
```
# php_mailer
