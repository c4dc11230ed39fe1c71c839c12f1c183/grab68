<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Helpers\Grab68GuzzleHelper;

class Grab68Controller extends Controller
{
    public function testEndpointWithGuzzle(Request $request)
    {
        $response = Grab68GuzzleHelper::scrape(route('endpoint-test.json'));
        dump($response);

        // return response()->json($response->json());
    }
}
