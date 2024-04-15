<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>

    @vite('resources/css/app.css')
</head>
<body class="emails">
<div class="email-container">
    <div class="email-wrapper">
        <div class="email-header">
            <h1 class="font-iranyekan-bold">{{ $siteTitle }}</h1>
            <span>-</span>
            <h2 class="font-iranyekan-light">{{ $title }}</h2>
        </div>
        <div class="email-body">
            {{ $slot }}
        </div>
        <div class="email-footer">
            <p class="font-iranyekan-light">
                &copy; {{ vertaTz(now())->format('Y') }} -
                تمامی حقوق متعلق به
                {{ $siteTitle }}
                می‌باشد
            </p>
        </div>
    </div>
</div>
</body>
</html>
