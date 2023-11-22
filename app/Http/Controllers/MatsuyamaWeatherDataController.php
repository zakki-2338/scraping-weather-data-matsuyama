<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\TryScraping;

use App\Models\MatsuyamaWeatherDatum;

class MatsuyamaWeatherDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function top()
    {
        return view('MatsuyamaWeatherData.top');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private $tryScraping;

    public function __construct(TryScraping $tryScraping)
    {
        $this->tryScraping = $tryScraping;
    }

    public function weatherDataStore(Request $request)
    {
        // 現在の日付を取得
        $currentDate = $request->input('currentDate');

        // 現在の年月日をCarbonオブジェクトに変換
        $currentDateObject = \Carbon\Carbon::parse($currentDate);
        
        // 去年の年月日を取得
        $oneYearAgoObject = $currentDateObject->subYear();
        $oneYearAgoYear = $oneYearAgoObject->format('Y');
        $oneYearAgoMonth = $oneYearAgoObject->format('m');
        $oneYearAgoDay = $oneYearAgoObject->format('d');
        
        // 昨日の年月日を取得
        $oneDayAgoObject = $currentDateObject->addYear()->subDay();
        $oneDayAgoYear = $oneDayAgoObject->format('Y');
        $oneDayAgoMonth = $oneDayAgoObject->format('m');
        $oneDayAgoDay = $oneDayAgoObject->format('d');

        MatsuyamaWeatherDatum::truncate();
        // URLを生成して出力
        for ($year = $oneYearAgoYear; $year <= $oneDayAgoYear; $year++) {
            // $yearが$oneYearAgoYearの場合は開始月を$oneYearAgoMonthから始める
            // そうじゃない場合は開始月を1月から始める
            $startMonth = ($year == $oneYearAgoYear) ? $oneYearAgoMonth : 1;
            // $yearが$currentYearの場合は終了月を$currentMonthとする
            // そうじゃない場合は終了月を12月とする
            $endMonth = ($year == $oneDayAgoYear) ? $oneDayAgoMonth : 12;
        
            for ($month = $startMonth; $month <= $endMonth; $month++) {
                // URLを生成
                $baseUrl = 'https://www.data.jma.go.jp/stats/etrn/view/daily_s1.php?prec_no=73&block_no=47887';
                $newUrl = $baseUrl . "&year={$year}&month={$month}&day=&view=p1";
                // サービスクラスのメソッドを呼び出す
                $this->tryScraping->tryScraping($oneYearAgoYear, $oneYearAgoMonth, $oneYearAgoDay, $oneDayAgoYear, $oneDayAgoMonth, $oneDayAgoDay, $year, $month, $newUrl);
            }
        }
        return redirect('/');
    }
}
