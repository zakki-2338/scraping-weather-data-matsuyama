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

    <div class="mt-10 mx-auto w-11/12 max-w-3xl">
        <!-- プルダウンメニュー -->
        <!-- セレクトボックスの値が変更されたときにupdateChart関数を呼び出す -->
        <select id="monthSelect" class="block ml-auto" onchange="updateChart()">
            @for ($year = $oneYearAgoYear; $year <= $oneDayAgoYear; $year++)
                @for ($month = ($year == $oneYearAgoYear ? $oneYearAgoMonth : 1); $month <= ($year == $oneDayAgoYear ? $oneDayAgoMonth : 12); $month++)
                    <option value="{{ sprintf('%d-%02d', $year, $month) }}" {{ $year == $oneDayAgoYear && $month == $oneDayAgoMonth ? 'selected' : '' }}>
                        {{ sprintf('%d-%02d', $year, $month) }}
                    </option>
                @endfor
            @endfor
        </select>

        <canvas id="weatherDataChart" class="mt-5 p-2 w-full aspect-video bg-white"></canvas>
    </div>

    <script>
        // 変数を初期化
        let weatherDataChart;

        function updateChart() {
            let selectedMonth = document.getElementById('monthSelect').value;

            // $matsuyamaWeatherDataから天気データを取得
            let matsuyamaWeatherData = {!! json_encode($matsuyamaWeatherData) !!};
    
            // 2023年11月分の日付、降水量合計、平均気温データを格納する空の配列を作成
            let dates = [];
            let precipitationTotal = [];
            let temperatureAvg = [];
    
            // 空の配列に、日付、降水量合計、平均気温データを格納
            matsuyamaWeatherData.forEach(function(data) {
                if (data.observation_date.includes(selectedMonth)) {
                    dates.push(data.observation_date);
                    precipitationTotal.push(data.precipitation_total);
                    temperatureAvg.push(data.temperature_avg);
                }
            });
            
            // 日付フォーマットを変更
            let dayLabels = dates.map(function(date) {
                return parseInt(date.split('-')[2]);  // 日付から日を取り出して整数に変換
            });
        
            // ID=weatherDataChartに対して2次元の描画コンテキストを取得
            // これにより線を引く、テキストを描画する等が可能
            // 2dは2D描画に特化した描画コンテキストを取得するメソッド
            let ctx = document.getElementById('weatherDataChart').getContext('2d');

            // グラフを描画する前に既存のChartを破棄
            if (weatherDataChart) {
                weatherDataChart.destroy();
            }

            // Chart.jsを使用して新しいチャートオブジェクトを作成
            weatherDataChart = new Chart(ctx, {
                data: {
                    datasets: [
                        {
                            data: temperatureAvg,
                            label: '平均気温',
                            type: 'line',
                            borderColor: 'red',
                            yAxisID: 'left-y-axis',
                        },
                        {
                            data: precipitationTotal,
                            label: '降水量合計',
                            type: 'bar',
                            backgroundColor: '#8EB4E3',
                            yAxisID: 'right-y-axis',
                        },
                    ],
                    labels: dayLabels
                },
                options: {
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom'
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        'left-y-axis': {
                            type: 'linear',
                            position: 'left',
                            title: {
                                display: true,
                                text: '気温(℃)'
                            },
                            grid: {
                                display: false
                            }
                        },
                        'right-y-axis': {
                            type: 'linear',
                            position: 'right',
                            title: {
                                display: true,
                                text: '降水量(mm)'
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // 初期表示時にもグラフを描画
        updateChart();
    </script>
@endif

@endsection