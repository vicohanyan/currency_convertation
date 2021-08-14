<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CurrencyTest extends TestCase
{
    public function testGetCurrency()
    {
        $response = $this->get('/currencies');

        $response->assertStatus(200);
    }

    public function testConvert(){
        $response = $this->get('/convert?from=USD&to=AMD&amount=1');
        $content = json_decode($response->getContent());
        $this->assertTrue($content->amount > 400);
        $response->assertStatus(200);
    }

    public function testConvertFromRUB(){
        $response = $this->get('/convert?from=RUB&to=AMD&amount=1');
        $content = json_decode($response->getContent());
        $this->assertTrue($content->amount > 5);
        $response->assertStatus(200);
    }

    public function testConvertToRUB(){
        $response = $this->get('/convert?from=USD&to=RUB&amount=50');
        $content = json_decode($response->getContent());
        $this->assertTrue($content->amount > 5);
        $response->assertStatus(200);
    }

    public function testConvertFromUsdToAmd(){
        $response = $this->get('/convert?from=USD&to=AMD&amount=50');
        $content = json_decode($response->getContent());
        $this->assertTrue($content->amount > 15000);
        $response->assertStatus(200);
    }

    public function testConvertWrongParams(){
        $response = $this->get('/convert?from=USD&to=AMD');
        $response->assertStatus(500);
        $response = $this->get('/convert?from=USD&amount=1');
        $response->assertStatus(500);
        $response = $this->get('/convert?to=USD&amount=1');
        $response->assertStatus(500);
    }

    public function testConvertWrongAmount(){
        $response = $this->get('/convert?from=USD&to=AMD&amount=78,3');
        $response->assertStatus(500);
    }

}
