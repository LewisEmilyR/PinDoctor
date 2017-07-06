<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pin extends Model
{
    protected $fillable = ['url', 'note', 'link', 'id'];
    public $valid;
    public $debug;

    /**
     * Does the pin have a link, and is that link valid?
     * @return boolean true if there is a link AND it is a valid link.
     */
    public function checkLink($pinLinkChecker)
    {
        if (empty($this->link)) {
            return false;
        }
        $linkResponse = $pinLinkChecker->getLink($this->link);
        if (($linkResponse->status > 199) && ($linkResponse->status < 300)) {
            return true;
        }

        return false;
    }

    /**
     * Output link status.
     * @return int 
     */
    public function debugLink($pinLinkChecker)
    {
        $linkResponse = $pinLinkChecker->getLink($this->link);
        return $linkResponse->status;
    }

}
