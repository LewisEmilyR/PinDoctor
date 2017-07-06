<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\GetPins;
use App\Pin;

class PublicPins extends Controller
{
    use GetPins;

    public function getPinsUrl($username)
    {
        return getenv('PINTEREST_PUBLIC_API_URL')."/users/{$username}/pins/";
    }

    public function getPins($username)
    {
        $url = $this->getPinsUrl($username);
        $data = $this->retreievePins($url);
        return Pin::hydrate($data->pins);
    }

}
