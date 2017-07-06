<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use App\Pin;
use Exception;

class PinsController extends Controller
{
    public function getPinsUrl()
    {
        return getenv('PINTEREST_API_URL').'/me/pins/';
    }

    public function getUrlParams($params = [])
    {
        if (empty($params['access_token'])) {
            $params['access_token'] = getenv('PINTEREST_ACCESS_TOKEN');
        }
        return '?'.http_build_query($params);
    }

    public function getPins($params = [])
    {
        $apiResponse = Curl::to($this->getPinsUrl().$this->getUrlParams($params))->asJsonResponse()->returnResponseObject()->get();
        if (!is_object($apiResponse) or !isset($apiResponse->status)) {
            throw new Exception("Problem connecting to Pinterest.");
        }
        if ($apiResponse->status < 200 or $apiResponse->status > 299) {
            throw new Exception('Error while connecting to Pinterest');
        }
        
       return Pin::hydrate($apiResponse->content->data);
    }

    public function inspectPins($pins)
    {
        if (empty($pins)) {
            throw new Exception("You don't seem to have any pins!");
        }

        $newCollection = $pins->map(function($pin) {
            $pin->valid = $pin->checkLink();
            return $pin;
        });

        return $newCollection;
    }
}
