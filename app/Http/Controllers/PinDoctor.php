<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;

class PinDoctor extends Controller
{
    public function index(PublicPins $pinsController, PinLinkChecker $linkChecker)
    {
        try {
            $pins = $pinsController->getPins('etailwind');
            $data = $pinsController->inspectPins($pins, $linkChecker);
            return view('pindoctor', ['error' => null, 'pins' => $data]);
        } catch (Exception $e) {
            return view('pindoctor', ['error' => $e->getMessage(), 'pins' => null]);
        }
    }
}
