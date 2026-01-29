<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تأكيد الطلب</title>
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
            background-color: #2563eb;
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
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .order-info h3 {
            margin-top: 0;
            color: #1e40af;
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
        .total-row {
            font-weight: bold;
            font-size: 18px;
            color: #1e40af;
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
            background-color: #2563eb;
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
            <h1>تم تأكيد طلبك بنجاح!</h1>
        </div>

        <div class="content">
            <p>مرحباً {{ $order->user->name }}،</p>
            <p>شكراً لطلبك من متجرنا. تم استلام طلبك وسيتم معالجته قريباً.</p>

            <div class="order-info">
                <h3>معلومات الطلب</h3>
                <p><strong>رقم الطلب:</strong> {{ $order->order_number }}</p>
                <p><strong>تاريخ الطلب:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
                <p><strong>طريقة الدفع:</strong>
                    @switch($order->payment_method)
                        @case('cash_on_delivery')
                            الدفع عند الاستلام
                            @break
                        @case('credit_card')
                            بطاقة ائتمان
                            @break
                        @case('bank_transfer')
                            تحويل بنكي
                            @break
                        @default
                            {{ $order->payment_method }}
                    @endswitch
                </p>
            </div>

            <h3>المنتجات المطلوبة</h3>
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
                    @foreach($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->product->name_ar ?? $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price, 2) }} د.ت</td>
                        <td>{{ number_format($item->subtotal, 2) }} د.ت</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="3">المجموع الفرعي</td>
                        <td>{{ number_format($order->subtotal, 2) }} د.ت</td>
                    </tr>
                    <tr>
                        <td colspan="3">الشحن</td>
                        <td>{{ number_format($order->shipping_cost, 2) }} د.ت</td>
                    </tr>
                    <tr>
                        <td colspan="3">الضريبة</td>
                        <td>{{ number_format($order->tax, 2) }} د.ت</td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="3">الإجمالي</td>
                        <td>{{ number_format($order->total, 2) }} د.ت</td>
                    </tr>
                </tbody>
            </table>

            <div class="order-info">
                <h3>عنوان التوصيل</h3>
                <p>{{ $order->shipping_address }}</p>
                <p>{{ $order->shipping_city }}, {{ $order->shipping_country }}</p>
                <p>الهاتف: {{ $order->shipping_phone }}</p>
            </div>

            <center>
                <a href="{{ route('orders.show', $order->id) }}" class="btn">تتبع الطلب</a>
            </center>
        </div>

        <div class="footer">
            <p>شكراً لتسوقك معنا!</p>
            <p>فريق Multivendor</p>
        </div>
    </div>
</body>
</html>
