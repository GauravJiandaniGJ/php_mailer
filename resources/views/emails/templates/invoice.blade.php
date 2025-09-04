<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
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
            background-color: #FF5722;
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
        .invoice-details {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .total {
            background-color: #FF5722;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 5px;
            margin: 20px 0;
            font-size: 18px;
            font-weight: bold;
        }
        .button {
            display: inline-block;
            background-color: #FF5722;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Invoice #{{ $invoice_number ?? '001' }}</h1>
    </div>
    <div class="content">
        <p>Dear {{ $customer_name ?? 'Customer' }},</p>
        
        <div class="invoice-details">
            <h3>Invoice Details</h3>
            <p><strong>Invoice Date:</strong> {{ $invoice_date ?? date('F j, Y') }}</p>
            <p><strong>Due Date:</strong> {{ $due_date ?? date('F j, Y', strtotime('+30 days')) }}</p>
            @if(isset($purchase_order))
            <p><strong>Purchase Order:</strong> {{ $purchase_order }}</p>
            @endif
        </div>
        
        @if(isset($items) && is_array($items))
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Rate</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>{{ $item['name'] ?? '' }}</td>
                    <td>{{ $item['quantity'] ?? 1 }}</td>
                    <td>${{ number_format($item['rate'] ?? 0, 2) }}</td>
                    <td>${{ number_format(($item['quantity'] ?? 1) * ($item['rate'] ?? 0), 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
        
        <div class="total">
            Total Amount: ${{ number_format($total_amount ?? 0, 2) }}
        </div>
        
        <p><strong>Payment Instructions:</strong></p>
        <p>{{ $payment_instructions ?? 'Please pay within 30 days of invoice date.' }}</p>
        
        @if(isset($payment_url))
        <a href="{{ $payment_url }}" class="button">Pay Now</a>
        @endif
        
        <p>Thank you for your business!</p>
        
        <p>Best regards,<br>{{ $company_name ?? 'Your Company' }}</p>
    </div>
</body>
</html>