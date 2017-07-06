<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Controllers\PublicPins;
use App\Http\Controllers\PinLinkChecker;
use App\Pin;
use Exception;

class GetPinsTest extends TestCase
{
    public function testPinsUrl()
    {
        $controller = new PublicPins();
        $url = $controller->getPinsUrl('etailwind');
        $this->assertEquals('https://api.pinterest.com/v3/pidgets/users/etailwind/pins/', $url);
    }

    public function testGetUrlParams()
    {
        $controller = new PublicPins();
        $queryString = $controller->getUrlParams([
            'access_token' => 'test',
        ]);
        $this->assertEquals('?access_token=test', $queryString);
    }

    public function testGetPinsFailure()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Error while connecting to Pinterest');
        $controller = new PublicPins();
        $pins = $controller->getPins(['fields' => 'test']);
        $this->assertContainsOnlyInstancesOf(Pin::class, $pins);
    }

    public function testGetPins()
    {
        $controller = new PublicPins();
        $pins = $controller->getPins('etailwind');
        $this->assertContainsOnlyInstancesOf(Pin::class, $pins);
        return $pins;
    }

    /**
     * @depends testGetPins
     */
    public function testInspectPins($pins)
    {
        $controller = new PublicPins();

        $mockStatus = json_decode('{"status": 200}');
        $tester = $this->createMock(PinLinkChecker::class);
        $tester->method('getLink')->willReturn($mockStatus);

        $data = $controller->inspectPins($pins, $tester);
        $this->assertContainsOnlyInstancesOf(Pin::class, $data);
        //test up to five pins for the desired attributes, to prevent this test from going overboard.
        if ($data->count() > 5) {
            $testThesePins = $data->random(5);
        } else {
            $testThesePins = $data;
        }
        foreach ($data as $pin) {
            $this->assertObjectHasAttribute('valid', $pin);
            $this->assertInternalType('boolean', $pin->valid);
        }
    }

    public function testInspectPinsEmpty($pins = [])
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("You don't seem to have any pins!");
        $controller = new PublicPins();
        $tester = $this->createMock(PinLinkChecker::class);
        $data = $controller->inspectPins($pins, $tester);
        $this->assertContainsOnlyInstancesOf(Pin::class, $data);
    }
}
