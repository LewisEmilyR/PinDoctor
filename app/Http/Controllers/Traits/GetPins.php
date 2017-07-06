<?php

namespace App\Http\Controllers\Traits;

use Ixudra\Curl\Facades\Curl;
use Exception;

trait GetPins 
{
    public function getUrlParams(array $params)
    {
        return '?'.http_build_query($params);
    }

    public function retreievePins($url)
    {
        $apiResponse = Curl::to($url)->asJsonResponse()->returnResponseObject()->get();
        if (!is_object($apiResponse) or !isset($apiResponse->status)) {
            throw new Exception("Problem connecting to Pinterest");
        }
        if ($apiResponse->status < 200 or $apiResponse->status > 299) {
            throw new Exception('Error while connecting to Pinterest: '.$url);
        }
        
       return $apiResponse->content->data;
    }

    public function inspectPins($pins, $linkChecker)
    {
        if (empty($pins)) {
            throw new Exception("You don't seem to have any pins!");
        }

        $newCollection = $pins->map(function($pin) use ($linkChecker) {
            $pin->valid = $pin->checkLink($linkChecker);
            return $pin;
        });

        return $newCollection;
    }
}
