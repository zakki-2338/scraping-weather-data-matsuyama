<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>WeatherDataMatsuyama</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="https://cdn.jsdelivr.net/npm/daisyui@2.24.0/dist/full.css" rel="stylesheet" type="text/css" />
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>

    <body class="bg-[#F2F0E9] pb-10 min-h-screen">
            {{-- エラーメッセージ --}}
            @include('Commons.error_messages')

            {{-- 指定したセクションをビューファイルの指定した場所に生み出す（呼び出す） --}}
            {{-- 継承先@section('content') --}}
            @yield('content')
    </body>
</html>