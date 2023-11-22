<?php

namespace App\Services;

use App\Models\MatsuyamaWeatherDatum;

class TryScraping
{
    public function tryScraping($oneYearAgoYear, $oneYearAgoMonth, $oneYearAgoDay, $oneDayAgoYear, $oneDayAgoMonth, $oneDayAgoDay, $year, $month, $newUrl)
    {
        // PHP Simple HTML DOM Parser の読み込み
        require_once public_path('simple_html_dom.php');
        // URLを指定してオブジェクト化
        $html = file_get_html($newUrl);
        // bodyタグを取得し、text部分を取り出す
        $mtxElements = $html->find('#tablefix1', 0)->find('.mtx');
        $mtxElementsInt = count($mtxElements);
        
        $startDay = ($year == $oneYearAgoYear && $month == $oneYearAgoMonth) ? $oneYearAgoDay+3 : 4;
        $endDay = ($year == $oneDayAgoYear && $month == $oneDayAgoMonth) ? $oneDayAgoDay+4 : $mtxElementsInt;

        // .mtxクラスの4番目以降について、textを表示
        for ($i = $startDay; $i < $endDay; $i++) {
            try {
                // 年と月を結合して表示
                $observation_date = $year . '-' . $month . '-' . $mtxElements[$i]->find('.a_print', 0)->plaintext;
                // 降水量の各要素が'--'の場合はnullと表示'
                $precipitation_total = $mtxElements[$i]->find('td', 3)->plaintext;
                $precipitation_total = str_replace([')', ']', ' '], '', $precipitation_total);
                $precipitation_total = $precipitation_total !== '--' ? $precipitation_total : null;
                $precipitation_max_1h = $mtxElements[$i]->find('td', 4)->plaintext;
                $precipitation_max_1h = str_replace([')', ']', ' '], '', $precipitation_max_1h);
                $precipitation_max_1h = $precipitation_max_1h !== '--' ? $precipitation_max_1h : null;
                $precipitation_max_10min = $mtxElements[$i]->find('td', 5)->plaintext;
                $precipitation_max_10min = str_replace([')', ']', ' '], '', $precipitation_max_10min);
                $precipitation_max_10min = $precipitation_max_10min !== '--' ? $precipitation_max_10min : null;
                $temperature_avg = $mtxElements[$i]->find('td', 6)->plaintext;
                $temperature_avg = str_replace([')', ']', ' '], '', $temperature_avg);
                $temperature_max = $mtxElements[$i]->find('td', 7)->plaintext;
                $temperature_max = str_replace([')', ']', ' '], '', $temperature_max);
                $temperature_min = $mtxElements[$i]->find('td', 8)->plaintext;
                $temperature_min = str_replace([')', ']', ' '], '', $temperature_min);
            
                // MatsuyamaWeatherDatumモデルのインスタンスを作成
                $matsuyamaWeatherDatum = new MatsuyamaWeatherDatum();

                // モデルのプロパティに値を設定
                $matsuyamaWeatherDatum->observation_date = $observation_date;
                $matsuyamaWeatherDatum->precipitation_total = $precipitation_total;
                $matsuyamaWeatherDatum->precipitation_max_1h = $precipitation_max_1h;
                $matsuyamaWeatherDatum->precipitation_max_10min = $precipitation_max_10min;
                $matsuyamaWeatherDatum->temperature_avg = $temperature_avg;
                $matsuyamaWeatherDatum->temperature_max = $temperature_max;
                $matsuyamaWeatherDatum->temperature_min = $temperature_min;

                // モデルをデータベースに保存
                $matsuyamaWeatherDatum->save();

            } catch (PDOException $e) {
                echo "エラー: " . $e->getMessage();
            }
        }
    }
}