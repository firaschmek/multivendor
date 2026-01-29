<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تم قبول طلبك</title>
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
            padding: 40px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .header .icon {
            font-size: 60px;
            margin-bottom: 15px;
        }
        .content {
            padding: 30px;
        }
        .welcome-box {
            background-color: #f0fdf4;
            border-radius: 8px;
            padding: 25px;
            margin: 20px 0;
            text-align: center;
            border: 2px dashed #059669;
        }
        .steps {
            margin: 30px 0;
        }
        .step {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        .step-number {
            background-color: #2563eb;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 15px;
            flex-shrink: 0;
            font-weight: bold;
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
            padding: 15px 40px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="icon">&#10004;</div>
            <h1>تهانينا!</h1>
            <p style="margin: 10px 0 0; font-size: 18px;">تم قبول طلبك كبائع</p>
        </div>

        <div class="content">
            <p>مرحباً {{ $vendor->user->name }}،</p>
            <p>يسعدنا إعلامك بأن طلبك للانضمام كبائع في منصة Multivendor قد تم قبوله!</p>

            <div class="welcome-box">
                <h2 style="color: #059669; margin-top: 0;">مرحباً بك في عائلة البائعين!</h2>
                <p>متجرك: <strong>{{ $vendor->shop_name_ar ?? $vendor->shop_name }}</strong></p>
            </div>

            <h3>الخطوات التالية:</h3>
            <div class="steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <div>
                        <strong>أكمل إعدادات متجرك</strong>
                        <p style="margin: 5px 0; color: #6b7280;">أضف شعار ووصف جذاب لمتجرك</p>
                    </div>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <div>
                        <strong>أضف منتجاتك</strong>
                        <p style="margin: 5px 0; color: #6b7280;">ابدأ بإضافة منتجاتك مع صور وأوصاف واضحة</p>
                    </div>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div>
                        <strong>ابدأ البيع!</strong>
                        <p style="margin: 5px 0; color: #6b7280;">استقبل الطلبات وابدأ في تحقيق الأرباح</p>
                    </div>
                </div>
            </div>

            <center>
                <a href="{{ route('vendor.dashboard') }}" class="btn">الذهاب للوحة التحكم</a>
            </center>

            <p style="margin-top: 30px;">إذا كان لديك أي استفسار، لا تتردد في التواصل معنا.</p>
        </div>

        <div class="footer">
            <p>نتمنى لك التوفيق والنجاح!</p>
            <p>فريق Multivendor</p>
        </div>
    </div>
</body>
</html>
