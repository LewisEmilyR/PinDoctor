<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ixudra\Curl\Facades\Curl;
use Cache;

class PinLinkChecker extends Controller
{
    public function getLink($link)
    {
        if (!Cache::has($link)) {
            $linkResponse = Curl::to($link)->returnResponseObject()->allowRedirect()->get();
            Cache::forever($link, $linkResponse);
        }
        $linkResponse  = Cache::get($link);
        return $linkResponse;
    }
}
