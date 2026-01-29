<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تم استلام طلبك</title>
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
        .info-box {
            background-color: #eff6ff;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border-right: 4px solid #2563eb;
        }
        .timeline {
            margin: 30px 0;
        }
        .timeline-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 25px;
        }
        .timeline-dot {
            width: 12px;
            height: 12px;
            background-color: #2563eb;
            border-radius: 50%;
            margin-left: 15px;
            margin-top: 5px;
            flex-shrink: 0;
        }
        .timeline-dot.pending {
            background-color: #d1d5db;
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>تم استلام طلب التسجيل</h1>
        </div>

        <div class="content">
            <p>مرحباً {{ $vendor->user->name }}،</p>
            <p>شكراً لاهتمامك بالانضمام كبائع في منصة Multivendor!</p>
            <p>تم استلام طلبك بنجاح وهو الآن قيد المراجعة.</p>

            <div class="info-box">
                <h3 style="margin-top: 0;">معلومات الطلب</h3>
                <p><strong>اسم المتجر:</strong> {{ $vendor->shop_name_ar ?? $vendor->shop_name }}</p>
                <p><strong>تاريخ التقديم:</strong> {{ $vendor->created_at->format('Y-m-d H:i') }}</p>
                <p><strong>الحالة:</strong> قيد المراجعة</p>
            </div>

            <h3>ماذا بعد؟</h3>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div>
                        <strong>تم استلام الطلب</strong>
                        <p style="margin: 5px 0; color: #6b7280;">تم استلام طلبك بنجاح</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-dot pending"></div>
                    <div>
                        <strong>مراجعة الطلب</strong>
                        <p style="margin: 5px 0; color: #6b7280;">سيقوم فريقنا بمراجعة معلوماتك</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-dot pending"></div>
                    <div>
                        <strong>إشعار بالنتيجة</strong>
                        <p style="margin: 5px 0; color: #6b7280;">ستتلقى إشعاراً بنتيجة الطلب خلال 24-48 ساعة</p>
                    </div>
                </div>
            </div>

            <p>إذا كان لديك أي استفسار، لا تتردد في التواصل معنا.</p>
        </div>

        <div class="footer">
            <p>شكراً لاختيارك Multivendor!</p>
            <p>فريق Multivendor</p>
        </div>
    </div>
</body>
</html>
