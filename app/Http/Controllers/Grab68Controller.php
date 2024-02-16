<?php

namespace App\Http\Controllers;

use App\Models\ExchangePair;
use App\Models\RatesHistory;
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
            'vcb'    => 'https://api.rate68.com/api/exchange-rate-bank/vietcombank?page=1&limit=10&sort=DESC&bank_code=vietcombank&client_id=2',
        ],

    ];

    public function testEndpointWithGuzzle(Request $request)
    {
        $response = app('grab68')->scrape(route('endpoint-test.json'));
        dump($response);

        // return response()->json($response->json());
    }

    public function getTyGia68GoldPrice($apiVersion = 'v1')
    {
        $response = app('grab68')->scrapeJson($this->tyGia68API[$apiVersion]['gold']);

        echo '<pre style="font-family: Courier New; font-size: 14px;">';
        if (!empty($response['data']) && is_array($response['data'])) {
            foreach ($response['data'] as $key => $value) {
                $code = strtolower(str_replace(' ', '', $value['code']));
                $description = strtolower(str_replace(' ', '', $value['name']));
                $name = $code . '/vnd';
                $pair = ExchangePair::addPair($name, $code, 'vnd', 'gold', $description);
                if (!empty($pair->id)) {
                    echo 'Pair ' . strtoupper($pair->name) . ' ' . ($pair->wasRecentlyCreated ? '<b style="color: green">created</b>' : '<b style="color: green">ok</b>') . '<br>';
                    $rate = RatesHistory::addRate($pair->id, $value['buyingPrice'], $value['sellingPrice']);
                    echo 'Rate entry ' . ($rate->wasRecentlyCreated ? '<b style="color: green">created</b>' : '<b style="color: orange">exists</b>') . ': ' . $rate->buy . ' - ' . $rate->sell . '<br><br>';
                }

                if (empty($pair->id) || empty($rate->id)) {
                    echo 'Error: ' . $pair->name . ' - ' . $value['buyingPrice'] . ' - ' . $value['sellingPrice'] . '<br><br>';
                }
            }
        }
        echo '</pre>';
    }

    // ! Only for Market, Emoney and USDT
    public function getTyGia68MarketPrice($type = 'market', $apiVersion = 'v1')
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
                    echo 'Error: ' . $pair->name . ' - ' . $value['buy'] . ' - ' . $value['sell'] . '<br><br>';
                }
            }
        }
        echo '</pre>';
    }
}
