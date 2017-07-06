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

    public function testDebugLink()
    {
        $pin = new Pin(['link' => 'https://a.link.that.exists/']);

        $mockStatus = json_decode('{"status": 200}');
        $tester = $this->createMock(PinLinkChecker::class);
        $tester->method('getLink')->willReturn($mockStatus);
        $this->assertEquals(200, $pin->debugLink($tester));

        $mockStatus = json_decode('{"status": 404}');
        $tester = $this->createMock(PinLinkChecker::class);
        $tester->method('getLink')->willReturn($mockStatus);
        $this->assertEquals(404, $pin->debugLink($tester));
    }
}
