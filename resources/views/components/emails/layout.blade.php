@php
    use App\Support\Converters\NumberConverter;
@endphp

    <!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>

    {{-- By using this, it'll NOT load css to show it but it works on email --}}
    {{-- 📍Laravel inline css package will automatically make all styles inline to make them show in actual emails --}}
    <link href="{{ resource_path('css/email.css') }}" rel="stylesheet">

    {{-- To see how email is look before sending you should follow this instruction: --}}
    {{-- 🙄You can do it on dev and vite but if you want it in production see below: --}}
    {{-- 1. Run 'npm run build' to build all css to public folder (customize if needed) --}}
    {{-- 2. use vite dirctive to load needed css "@vite('resources/css/my-css.css')" --}}
    {{-- @vite('resources/css/my-css.css') --}}
</head>
<body class="emails" dir="rtl">
@props([
    'bgColor' => '#3057D3',
    'siteTitle',
    'title',
])

<div class="email-container font-iranyekan-regular">
    <div class="email-wrapper">
        <div class="email-header" style="background-color: {{ $bgColor  }};">
            <h1 class="font-iranyekan-bold">{{ $siteTitle }}</h1>
            <span>-</span>
            <h2 class="font-iranyekan-light">{{ $title }}</h2>
        </div>
        <div class="email-body">
            {{ $slot }}
        </div>
        <div class="email-footer">
            <p class="font-iranyekan-light">
                &copy; {{ NumberConverter::toPersian(vertaTz(now())->format('Y')) }} -
                تمامی حقوق متعلق به
                {{ $siteTitle }}
                می‌باشد
            </p>
        </div>
    </div>
</div>
</body>
</html>
