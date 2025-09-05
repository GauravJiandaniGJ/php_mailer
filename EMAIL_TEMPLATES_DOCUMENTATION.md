# Email Templates Documentation

## API Payload Structure

```json
{
  "to": ["recipient@example.com"],
  "from": "sender@example.com", 
  "subject": "Email Subject",
  "template_type": "resetpassword|welcome|custom",
  "data": {
    // Template-specific parameters
  }
}
```

## Template Types

### 1. Reset Password (`resetpassword`)

**Required Parameters:**
- `name` - Recipient's name
- `otp` - One-time password/verification code
- `message` - Password reset message

**Optional Parameters:**
- `otp_expiry_minutes` - OTP expiration time (default: 15)
- `reset_url` - Direct reset link (if available)
- `company` - Company name (default: from config)
- `support_email` - Support contact email
- `additional_info` - Extra information to display
- `unsubscribe_url` - Unsubscribe link

**Example Payload:**
```json
{
  "to": ["user@example.com"],
  "from": "noreply@yourapp.com",
  "subject": "Reset Your Password",
  "template_type": "resetpassword",
  "data": {
    "name": "John Doe",
    "otp": "123456",
    "message": "You requested a password reset. Use the code below to reset your password.",
    "otp_expiry_minutes": "15",
    "reset_url": "https://yourapp.com/reset-password?token=abc123",
    "company": "YourApp",
    "support_email": "support@yourapp.com",
    "additional_info": "If you didn't request this, contact support immediately."
  }
}
```

### 2. Welcome Email (`welcome`)

**Required Parameters:**
- `name` - User's name
- `welcome_message` - Personal welcome message

**Optional Parameters:**
- `email` - User's email (for account details)
- `username` - User's username
- `signup_date` - Account creation date
- `subscription_plan` - User's subscription plan
- `app_url` - Link to the application
- `show_tips` - Boolean to show getting started tips
- `special_offer` - Object with offer details
- `offer_description` - Description of special offer
- `offer_code` - Promo code
- `offer_expires` - Offer expiration date
- `support_email` - Support contact
- `help_center_url` - Help documentation link
- `support_hours` - Support availability hours
- `social_links` - Object with social media URLs
- `twitter_url` - Twitter profile URL
- `facebook_url` - Facebook profile URL
- `instagram_url` - Instagram profile URL
- `company` - Company name
- `privacy_policy_url` - Privacy policy link
- `unsubscribe_url` - Unsubscribe link

**Example Payload:**
```json
{
  "to": ["newuser@example.com"],
  "from": "welcome@giftai.com",
  "subject": "Welcome to GiftAI!",
  "template_type": "welcome",
  "data": {
    "name": "Sarah Johnson",
    "welcome_message": "We're thrilled to have you join our community of thoughtful gift-givers!",
    "email": "newuser@example.com",
    "username": "sarah_j",
    "signup_date": "January 15, 2024",
    "subscription_plan": "Premium",
    "app_url": "https://giftai.com/dashboard",
    "show_tips": true,
    "account_details": true,
    "special_offer": {
      "offer_description": "Get 20% off your first gift recommendation!",
      "offer_code": "WELCOME20",
      "offer_expires": "February 15, 2024"
    },
    "support_email": "support@giftai.com",
    "help_center_url": "https://help.giftai.com",
    "support_hours": "Mon-Fri 9AM-5PM EST",
    "social_links": {
      "twitter_url": "https://twitter.com/giftai",
      "instagram_url": "https://instagram.com/giftai"
    },
    "company": "GiftAI",
    "privacy_policy_url": "https://giftai.com/privacy",
    "unsubscribe_url": "https://giftai.com/unsubscribe"
  }
}
```

### 3. Custom Template (`custom`)

For custom templates, you can either:

**Option A: Provide HTML content directly**
```json
{
  "template_type": "custom",
  "html_content": "<html>Your custom HTML here with {{placeholders}}</html>",
  "data": {
    "placeholder_key": "value"
  }
}
```

**Option B: Use existing template file**
```json
{
  "template_type": "custom",
  "template_id": "your_custom_template",
  "data": {
    "your_data": "here"
  }
}
```

## Global Parameters

These parameters are automatically available in all templates:

- `timestamp` - Current timestamp (Y-m-d H:i:s format)
- `date` - Current date (Y-m-d format)
- `time` - Current time (H:i:s format)
- `year` - Current year

## Conditional Content

Templates support conditional content using Mustache-style syntax:

```html
{{#parameter_name}}
  Content shown when parameter exists and is truthy
{{/parameter_name}}

{{^parameter_name}}
  Content shown when parameter doesn't exist or is falsy
{{/parameter_name}}
```

## Template File Structure

Templates are stored in: `/resources/email-templates/`

- `resetpassword.html` - Password reset template
- `welcome.html` - Welcome email template
- Custom templates can be added with any name

## Error Handling

The API will return appropriate error messages for:
- Missing required parameters
- Invalid template types
- Template files not found
- Invalid email addresses
- Missing template_type or data fields

## Response Format

**Success Response:**
```json
{
  "status": "success",
  "message": "Email sent successfully",
  "recipients": 1
}
```

**Error Response:**
```json
{
  "status": "error",
  "message": "Error description",
  "errors": {
    "field": ["validation error"]
  }
}
```