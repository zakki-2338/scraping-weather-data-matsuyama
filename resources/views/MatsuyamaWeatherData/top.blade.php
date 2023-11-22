{{-- 引数でビューファイル名（ブレード名）を指定することで、指定したファイルの中身をそのまま表示する --}}
@extends('Layouts.app')


{{-- @extendsで呼び出したビューファイルの中の、対応する@yeildに情報を表示するためのディレクティブ --}}
@section('content')

<h1 class="pt-10 text-2xl text-center">松山市の気象データ</h1>
<h2 class="mt-1 text-base text-center">過去1年間の気象データを取得できます</h2>
<div class="mt-10 flex justify-center items-center">
    <h3>本日の日付:{{ date('Y年m月d日') }}</h3>
    <form action="{{ route('weather.data.store') }}" method="POST">
        @csrf
        <input type="hidden" name="currentDate" value="{{ date('Y-m-d') }}">
        <button type="submit" class="btn btn-active btn-ghost btn-sm ml-5">取得する</button>
    </form>
</div>


@endsection