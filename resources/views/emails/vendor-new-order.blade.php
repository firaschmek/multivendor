<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلب جديد</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
            line-height: 1.8;
            color: #333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            direction: rtl;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #059669;
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .order-info {
            background-color: #f0fdf4;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border-right: 4px solid #059669;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .items-table th, .items-table td {
            padding: 12px;
            text-align: right;
            border-bottom: 1px solid #e5e7eb;
        }
        .items-table th {
            background-color: #f3f4f6;
            font-weight: 600;
        }
        .earnings {
            background-color: #fef3c7;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .earnings-amount {
            font-size: 28px;
            font-weight: bold;
            color: #d97706;
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            background-color: #059669;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>لديك طلب جديد!</h1>
        </div>

        <div class="content">
            <p>مرحباً {{ $vendor->shop_name_ar ?? $vendor->shop_name }}،</p>
            <p>لديك طلب جديد يحتاج إلى معالجته.</p>

            <div class="order-info">
                <p><strong>رقم الطلب:</strong> {{ $order->order_number }}</p>
                <p><strong>تاريخ الطلب:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
                <p><strong>اسم العميل:</strong> {{ $order->user->name }}</p>
            </div>

            <h3>منتجاتك في هذا الطلب</h3>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>المنتج</th>
                        <th>الكمية</th>
                        <th>السعر</th>
                        <th>المجموع</th>
                    </tr>
                </thead>
                <tbody>
                    @php $vendorTotal = 0; $vendorEarnings = 0; @endphp
                    @foreach($vendorItems as $item)
                    @php
                        $vendorTotal += $item->subtotal;
                        $vendorEarnings += $item->vendor_amount;
                    @endphp
                    <tr>
                        <td>{{ $item->product->name_ar ?? $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price, 2) }} د.ت</td>
                        <td>{{ number_format($item->subtotal, 2) }} د.ت</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="earnings">
                <p>أرباحك من هذا الطلب</p>
                <div class="earnings-amount">{{ number_format($vendorEarnings, 2) }} د.ت</div>
                <p style="font-size: 14px; color: #6b7280;">(بعد خصم العمولة)</p>
            </div>

            <div class="order-info">
                <h3>عنوان التوصيل</h3>
                <p>{{ $order->shipping_address }}</p>
                <p>{{ $order->shipping_city }}, {{ $order->shipping_country }}</p>
                <p>الهاتف: {{ $order->shipping_phone }}</p>
            </div>

            <center>
                <a href="{{ route('vendor.orders.show', $order->id) }}" class="btn">عرض تفاصيل الطلب</a>
            </center>
        </div>

        <div class="footer">
            <p>يرجى معالجة الطلب في أقرب وقت ممكن.</p>
            <p>فريق Multivendor</p>
        </div>
    </div>
</body>
</html>
