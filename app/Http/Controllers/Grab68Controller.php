<?php

namespace App\Http\Controllers;

use App\Models\ExchangePair;
use App\Models\NiceHistory;
use App\Models\RatesHistory;
use App\Models\VcbHistory;
use Illuminate\Http\Request;

class Grab68Controller extends Controller
{
    public $tyGia68API = [
        'v1' => [
            'market' => 'https://api.rate68.com/api/exchange-rate/list-exchange?page=1&limit=100&type=market_price&client_id=2',
            'emoney' => 'https://api.rate68.com/api/exchange-rate/list-exchange?page=1&limit=100&type=currency_to_e_currency&source_id=4&client_id=2',
            'usdt'   => 'https://api.rate68.com/api/exchange-rate/list-exchange?page=1&limit=100&type=usdt_to_fiat&client_id=2',
            'gold'   => 'https://api.rate68.com/api/exchange-rate/gold-price-v2?client_id=2',
            'nice'   => 'https://api.rate68.com/api/exchange-rate/nice-price?client_id=2',
            // 'gold2'  => 'https://api.rate68.com/api/exchange-rate/gold-reference?client_id=2',
            'vcb'    => 'https://api.rate68.com/api/exchange-rate-bank/vietcombank?page=1&limit=100&sort=DESC&bank_code=vietcombank&client_id=2',
        ],

    ];

    public function testEndpointWithGuzzle(Request $request)
    {
        $response = app('grab68')->scrape(route('endpoint-test.json'));
        dump($response);

        // return response()->json($response->json());
    }

    public function getTyGia68VcbPrice($apiVersion = 'v1')
    {
        $response = app('grab68')->scrapeJson($this->tyGia68API[$apiVersion]['vcb']);

        echo '<pre style="font-family: Courier New; font-size: 14px;">';
        if (!empty($response['data']) && is_array($response['data'])) {
            foreach ($response['data'] as $key => $value) {
                $currency = strtolower(str_replace(' ', '', $value['exchange_name']));
                $buyTm = $this->reformatNumber($value['buy_TM']);
                $buyCk = $this->reformatNumber($value['buy_CK']);
                $sellTm = $this->reformatNumber($value['sell_TM']);
                $sellCk = $this->reformatNumber($value['sell_CK']);

                if (!empty($currency) && (!empty($buyTm) || !empty($sellTm) || !empty($buyCk) || !empty($sellCk))) {
                    $history = VcbHistory::addHistory($currency, $buyTm, $buyCk, $sellTm, $sellCk);
                    echo 'History entry ' . ($history->wasRecentlyCreated ? '<b style="color: green">created</b>' : '<b style="color: orange">exists</b>') . ': ' . $history->currency . ' - ' . $history->buy_tm . ' - ' . $history->buy_ck . ' - ' . $history->sell_tm . ' - ' . $history->sell_ck . '<br>';
                } else {
                    echo '<b style="color: red">Error: ' . ($currency ?? 'Currency') . ' - ' . ($buyTm ?? 'Buy TM') . ' - ' . ($sellTm ?? 'Sell TM') . ' - ' . ($buyCk ?? 'Buy CK') . ' - ' . ($sellCk ?? 'Sell CK') . '</b><br>';
                }
            }
        }
        echo '</pre>';
    }

    public function getTyGia68NicePrice($apiVersion = 'v1')
    {
        $response = app('grab68')->scrapeJson($this->tyGia68API[$apiVersion]['nice']);

        echo '<pre style="font-family: Courier New; font-size: 14px;">';
        if (!empty($response['data'][0]) && is_array($response['data'][0])) {
            foreach ($response['data'][0] as $key => $value) {
                if (substr(strtolower($key), 0, 3) == 'sjc') {
                    $sjc = $value;
                } elseif (substr(strtolower($key), 0, 4) == 'bdep') {
                    $nice = $value;
                }
            }

            if (!empty($sjc) && !empty($nice)) {
                $history = NiceHistory::addHistory($sjc, $nice);
                echo 'History entry ' . ($history->wasRecentlyCreated ? '<b style="color: green">created</b>' : '<b style="color: orange">exists</b>') . ': ' . $history->sjc . ' - ' . $history->nice . '<br>';
            } else {
                echo '<b style="color: red">Error: ' . ($sjc ?? 'SJC') . ' - ' . ($nice ?? 'NICE') . '</b><br>';
            }
        }
        echo '</pre>';
    }

    public function getTyGia68GoldPrice($apiVersion = 'v1')
    {
        $response = app('grab68')->scrapeJson($this->tyGia68API[$apiVersion]['gold']);

        echo '<pre style="font-family: Courier New; font-size: 14px;">';
        if (!empty($response['data']) && is_array($response['data'])) {
            foreach ($response['data'] as $key => $value) {
                $code = strtolower(str_replace(' ', '', $value['code']));
                $description = mb_convert_case($value['name'], MB_CASE_TITLE, 'UTF-8');
                $name = $code . '/vnd';
                $pair = ExchangePair::addPair($name, $code, 'vnd', 'gold', $description);
                if (!empty($pair->id)) {
                    echo 'Pair ' . strtoupper($pair->name) . ' ' . ($pair->wasRecentlyCreated ? '<b style="color: green">created</b>' : '<b style="color: green">ok</b>') . '<br>';
                    $rate = RatesHistory::addRate($pair->id, $value['buyingPrice'], $value['sellingPrice']);
                    echo 'Rate entry ' . ($rate->wasRecentlyCreated ? '<b style="color: green">created</b>' : '<b style="color: orange">exists</b>') . ': ' . $rate->buy . ' - ' . $rate->sell . '<br><br>';
                }

                if (empty($pair->id) || empty($rate->id)) {
                    echo '<b style="color: red">Error: ' . $pair->name . ' - ' . $value['buyingPrice'] . ' - ' . $value['sellingPrice'] . '</b><br><br>';
                }
            }
        }
        echo '</pre>';
    }

    public function getTyGia68MarketPrice($apiVersion = 'v1') {
        $this->getTyGia68Price('market', $apiVersion);
    }

    public function getTyGia68eMoneyPrice($apiVersion = 'v1') {
        $this->getTyGia68Price('emoney', $apiVersion);
    }

    public function getTyGia68UsdtPrice($apiVersion = 'v1') {
        $this->getTyGia68Price('usdt', $apiVersion);
    }

    // ! Only for Market, Emoney and USDT
    public function getTyGia68Price($type = 'market', $apiVersion = 'v1')
    {
        $response = app('grab68')->scrapeJson($this->tyGia68API[$apiVersion][$type]);

        echo '<pre style="font-family: Courier New; font-size: 14px;">';
        if (!empty($response['data']['data'])) {
            foreach ($response['data']['data'] as $key => $value) {
                $base = strtolower(str_replace(' ', '', $value['from']));
                $quote = strtolower(str_replace(' ', '', $value['to']));
                $name = $base . '/' . $quote;
                $pair = ExchangePair::addPair($name, $base, $quote, $type);
                if (!empty($pair->id)) {
                    echo 'Pair ' . strtoupper($pair->name) . ' ' . ($pair->wasRecentlyCreated ? '<b style="color: green">created</b>' : '<b style="color: green">ok</b>') . '<br>';
                    $rate = RatesHistory::addRate($pair->id, $value['buy'], $value['sell']);
                    echo 'Rate entry ' . ($rate->wasRecentlyCreated ? '<b style="color: green">created</b>' : '<b style="color: orange">exists</b>') . ': ' . $rate->buy . ' - ' . $rate->sell . '<br><br>';
                }

                if (empty($pair->id) || empty($rate->id)) {
                    echo '<b style="color: red">Error: ' . $pair->name . ' - ' . $value['buy'] . ' - ' . $value['sell'] . '</b><br><br>';
                }
            }
        }
        echo '</pre>';
    }

    public function reformatNumber($number)
    {
        $number = str_replace('.', '', $number);
        $number = str_replace(',', '.', $number);

        if (is_numeric($number)) {
            return number_format($number, 2, '.', '');
        }

        return null;
    }

    public function getWiseSwiftCode($swiftCode)
    {
        $response = app('grab68')->scrape('https://transferwise.com/gb/swift-codes/' . $swiftCode);

        dump($response);

        echo $response;
    }
}
