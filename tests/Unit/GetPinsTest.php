<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Pin;
use App\Http\Controllers\PinsController;
use Exception;

class GetPinsTest extends TestCase
{
    public function testGetPinsUrl()
    {
        $controller = new PinsController();
        $url = $controller->getPinsUrl();
        $this->assertEquals('https://api.pinterest.com/v1/me/pins/', $url);
    }

    public function testGetUrlParams()
    {
        $controller = new PinsController();
        $queryString = $controller->getUrlParams([
            'access_token' => 'test',
        ]);
        $this->assertEquals('?access_token=test', $queryString);
    }

    public function testGetPinsFailure()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Error while connecting to Pinterest');
        $controller = new PinsController();
        $pins = $controller->getPins(['fields' => 'test']);
        $this->assertContainsOnlyInstancesOf(Pin::class, $pins);
    }

    public function testGetPins()
    {
        $controller = new PinsController();
        $pins = $controller->getPins();
        $this->assertContainsOnlyInstancesOf(Pin::class, $pins);
        return $pins;
    }

    /**
     * @depends testGetPins
     */
    public function testInspectPins($pins)
    {
        $controller = new PinsController();
        $data = $controller->inspectPins($pins);
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
        $controller = new PinsController();
        $data = $controller->inspectPins($pins);
        $this->assertContainsOnlyInstancesOf(Pin::class, $data);
    }
}
