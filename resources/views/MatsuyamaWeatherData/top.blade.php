{{-- 引数でビューファイル名（ブレード名）を指定することで、指定したファイルの中身をそのまま表示する --}}
@extends('Layouts.app')


{{-- @extendsで呼び出したビューファイルの中の、対応する@yeildに情報を表示するためのディレクティブ --}}
@section('content')

<h1 class="pt-10 text-2xl text-center">愛媛県松山市の気象データ</h1>
<h2 class="mt-1 text-base text-center">過去1年間の気象データを取得できます</h2>
<div class="mt-10 flex justify-center items-center">
    <h3>本日の日付:{{ date('Y年m月d日') }}</h3>
    <form action="{{ route('weather.data.store') }}" method="POST">
        @csrf
        <input type="hidden" name="currentDate" value="{{ date('Y-m-d') }}">
        <button type="submit" class="btn btn-active btn-ghost btn-sm ml-5">取得する</button>
    </form>
</div>

@if(isset($matsuyamaWeatherData) && $matsuyamaWeatherData->count() > 0)
    <table class="mt-5 mx-auto border border-collapse border-slate-400">
        <thead>
            <tr>
                <th rowspan="3" class="border border-slate-400">日</th>
                <th colspan="3" class="border border-slate-400">降水量&lpar;mm&rpar;</th>
                <th colspan="3" class="border border-slate-400">気温&lpar;&#8451;&rpar;</th>
            </tr>
            <tr>
                <th rowspan="2" class="px-2 border border-slate-400">合計</th>
                <th colspan="2" class="border border-slate-400">最大</th>
                <th rowspan="2" class="px-2 border border-slate-400">平均</th>
                <th rowspan="2" class="px-2 border border-slate-400">最高</th>
                <th rowspan="2" class="px-2 border border-slate-400">最低</th>
            </tr>
            <tr>
                <th class="px-2 border border-slate-400">1時間</th>
                <th class="px-2 border border-slate-400">10分間</th>
            </tr>
        </thead>
        <tbody>
            <!-- データの表示 -->
            @foreach($matsuyamaWeatherData as $data)
                <tr>
                    <td class="px-2 border border-slate-400">{{ $data->observation_date }}</td>
                    <td class="text-center border border-slate-400">{{ $data->precipitation_total !== null ? number_format($data->precipitation_total, 1) : '--' }}</td>
                    <td class="text-center border border-slate-400">{{ $data->precipitation_max_1h !== null ? number_format($data->precipitation_max_1h, 1) : '--' }}</td>
                    <td class="text-center border border-slate-400">{{ $data->precipitation_max_10min !== null ? number_format($data->precipitation_max_10min, 1) : '--' }}</td>
                    <td class="text-center border border-slate-400">{{ $data->temperature_avg !== null ? number_format($data->temperature_avg, 1) : '--' }}</td>
                    <td class="text-center border border-slate-400">{{ $data->temperature_max !== null ? number_format($data->temperature_max, 1) : '--' }}</td>
                    <td class="text-center border border-slate-400">{{ $data->temperature_min !== null ? number_format($data->temperature_min, 1) : '--' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

@endsection