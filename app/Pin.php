<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Ixudra\Curl\Facades\Curl;

class Pin extends Model
{
    protected $fillable = ['url', 'note', 'link', 'id'];
    public $valid;

    /**
     * Does the pin have a link, and is that link valid?
     * @return boolean true if there is a link AND it is a valid link.
     */
    public function checkLink()
    {
        if (empty($this->link)) {
            return false;
        }
        $linkResponse = Curl::to($this->link)->returnResponseObject()->get();
        if ($linkResponse->status < 200 or $linkResponse->status > 299) {
            return false;
        }

        return true;
    }
}
