<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Controllers\PinLinkChecker;
use App\Pin;

class PinTest extends TestCase
{
    public function testCheckEmptyLink()
    {
        $pin = new Pin(['link' => null]);
        $tester = new PinLinkChecker();
        $this->assertFalse($pin->checkLink($tester));
    }

    public function testValidLink()
    {
        $pin = new Pin(['link' => 'https://a.link.that.exists/']);
        $mockStatus = json_decode('{"status": 200}');
        $tester = $this->createMock(PinLinkChecker::class);
        $tester->method('getLink')->willReturn($mockStatus);
        $this->assertTrue($pin->checkLink($tester));
    }

    public function testInvalidLink()
    {
        $pin = new Pin(['link' => 'https://a.link.that.exists/']);
        $mockStatus = json_decode('{"status": 404}');
        $tester = $this->createMock(PinLinkChecker::class);
        $tester->method('getLink')->willReturn($mockStatus);
        $this->assertFalse($pin->checkLink($tester));
    }

    public function testRatio()
    {
        $img = json_decode('{"264x": {
            "height": 1000,
            "width": 100
        }}');
        $pin = new Pin(['images' => $img]);
        $this->assertEquals(10, $pin->ratio);
    }

    public function testCheckRatio()
    {
        $height = 100;
        $width = 50;
        $img = json_decode('{"264x": {
            "height": '.$height.',
            "width": '.$width.'
        }}');
        $pin = new Pin(['images' => $img]);
        $this->assertEquals(2, $pin->ratio);
        $this->assertFalse($pin->checkRatio(1.2));
        $this->assertTrue($pin->checkRatio(3));
    }
}
