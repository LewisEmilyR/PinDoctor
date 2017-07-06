<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\GetPins;
use App\Pin;

class AccessCodePins extends Controller
{
    use GetPins;

    public function getPinsUrl()
    {
        return getenv('PINTEREST_PRIVATE_API_URL').'/me/pins/';
    }

    public function getPins($params = ['fields' => 'id,link,url,note,metadata'])
    {
        if (empty($params['access_token'])) {
            $params['access_token'] = getenv('PINTEREST_ACCESS_TOKEN');
        }
        
        $url = $this->getPinsUrl().$this->getUrlParams($params);
        $data = $this->retreievePins($url);
        return Pin::hydrate($data);
    }
}
