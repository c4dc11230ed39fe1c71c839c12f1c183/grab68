<?php

namespace App\Http\Controllers;

use App\Models\ExchangePair;
use App\Models\RatesHistory;
use Illuminate\Http\Request;

class Grab68Controller extends Controller
{
    public $tyGia68API = [
        'v1' => [
            'market_price'           => 'https://api.rate68.com/api/exchange-rate/list-exchange?page=1&limit=100&type=market_price&client_id=2',
            'currency_to_e_currency' => 'https://api.rate68.com/api/exchange-rate/list-exchange?page=1&limit=100&type=currency_to_e_currency&source_id=4&client_id=2',
            'usdt_to_fiat'           => 'https://api.rate68.com/api/exchange-rate/list-exchange?page=1&limit=100&type=usdt_to_fiat&client_id=2',
            'gold_price'             => 'https://api.rate68.com/api/exchange-rate/gold-price-v2?client_id=2',
            'nice_price'             => 'https://api.rate68.com/api/exchange-rate/nice-price?client_id=2',
            'gold_reference'         => 'https://api.rate68.com/api/exchange-rate/gold-reference?client_id=2',
            'vietcombank'            => 'https://api.rate68.com/api/exchange-rate-bank/vietcombank?page=1&limit=10&sort=DESC&bank_code=vietcombank&client_id=2',
        ],

    ];

    public function testEndpointWithGuzzle(Request $request)
    {
        $response = app('grab68')->scrape(route('endpoint-test.json'));
        dump($response);

        // return response()->json($response->json());
    }

    public function getTyGia68MarketPrice($apiVersion = 'v1')
    {
        $response = app('grab68')->scrapeJson($this->tyGia68API[$apiVersion]['market_price']);

        echo '<pre style="font-family: Courier New; font-size: 14px;">';
        if (!empty($response['data']['data'])) {
            foreach ($response['data']['data'] as $key => $value) {
                $base = strtolower($value['from']);
                $quote = strtolower($value['to']);
                $name = $base . '/' . $quote;
                $pair = ExchangePair::addPair($name, $base, $quote);
                if (!empty($pair->id)) {
                    echo 'Pair ' . strtoupper($pair->name) . ' ' . ($pair->wasRecentlyCreated ? 'created' : '<b style="color: green">ok</b>') . '<br>';
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

    public function getTyGia68eCurrencyPrice($apiVersion = 'v1') {
        $response = app('grab68')->scrapeJson($this->tyGia68API[$apiVersion]['currency_to_e_currency']);

        echo '<pre style="font-family: Courier New; font-size: 14px;">';
        if (!empty($response['data']['data'])) {
            foreach ($response['data']['data'] as $key => $value) {
                $base = strtolower($value['from']);
                $quote = strtolower(str_replace(' ', '', $value['to']));
                $name = $base . '/' . $quote;
                $pair = ExchangePair::addPair($name, $base, $quote);
                if (!empty($pair->id)) {
                    echo 'Pair ' . strtoupper($pair->name) . ' ' . ($pair->wasRecentlyCreated ? 'created' : '<b style="color: green">ok</b>') . '<br>';
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
