<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ixudra\Curl\Facades\Curl;

class PinLinkChecker extends Controller
{
    public function getLink($link)
    {
        $linkResponse = Curl::to($link)->returnResponseObject()->allowRedirect()->get();
        return $linkResponse;
    }
}
