<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pin extends Model
{
    protected $fillable = ['url', 'note', 'link', 'id', 'images'];
    public $valid;
    public $status;
    protected $ratio;
    protected $validRatio;

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
        $this->status = $linkResponse->status;
        if (($linkResponse->status > 199) && ($linkResponse->status < 300)) {
            return true;
        }

        return false;
    }

    public function getRatioAttribute()
    {
        if (empty($this->ratio)) {
            $images = (array)$this->images;
            $imageDesc = array_shift($images);
            $this->ratio = round($imageDesc->height/$imageDesc->width, 2);
        }

        return $this->ratio;
    }
}
