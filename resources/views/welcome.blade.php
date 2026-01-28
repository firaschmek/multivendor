<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'لارافل') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cairo:400,500,600,700" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
    <style>
        /* Base font override for Arabic */
        :root { --font-sans: 'Cairo', sans-serif; }
        /* ... rest of your tailwind CSS ... */
    </style>
    @endif
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col font-sans">
<header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
    @if (Route::has('login'))
    <nav class="flex items-center justify-start gap-4">
        @auth
        <a
            href="{{ url('/dashboard') }}"
            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal"
        >
            لوحة التحكم
        </a>
        @else
        <a
            href="{{ route('login') }}"
            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal"
        >
            تسجيل الدخول
        </a>

        @if (Route::has('register'))
        <a
            href="{{ route('register') }}"
            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
            إنشاء حساب
        </a>
        @endif
        @endauth
    </nav>
    @endif
</header>
<div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
    <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row">
        <div class="text-[13px] leading-[20px] flex-1 p-6 pb-12 lg:p-20 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-br-lg rounded-bl-lg lg:rounded-tr-lg lg:rounded-bl-none">
            <h1 class="mb-1 font-medium text-lg">لنبدأ العمل</h1>
            <p class="mb-2 text-[#706f6c] dark:text-[#A1A09A]">لارافل تمتلك نظاماً بيئياً غنياً بشكل لا يصدق. <br>نقترح البدء بما يلي:</p>

            {{-- Note: The rest of the list structure follows here --}}
            <ul class="flex flex-col mb-4 lg:mb-6">
                <li class="flex items-center gap-4 py-2 relative before:border-r before:border-[#e3e3e0] dark:before:border-[#3E3E3A] before:top-1/2 before:bottom-0 before:right-[0.4rem] before:absolute">
                    <span class="relative py-1 bg-white dark:bg-[#161615] px-1">التوثيق</span>
                </li>
            </ul>
        </div>
    </main>
</div>
</body>
</html>
