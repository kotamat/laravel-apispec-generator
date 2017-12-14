<?php

namespace Test\ApiSpec;

use ApiSpec\ApiSpecObject;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use PHPUnit\Framework\TestCase;

class ApiSpecTest extends TestCase
{
    /**
     * @test
     */
    public function TestGenerateContent_GET()
    {
        $content = (new ApiSpecObject())->setMethod('GET')
            ->setUri('http://hoge.com/user/1')
            ->setResponse(new TestResponse(new Response(['name' => 'huga'])))
            ->generateContent();

        $expected = <<< EOS
GET http://hoge.com/user/1

# Response:
#{
#    "name": "huga"
#}
EOS;

        $this->assertEquals($expected, $content);
    }

    /**
     * @test
     */
    public function TestGenerateContent_WithData()
    {
        $content = (new ApiSpecObject())->setMethod('POST')
            ->setUri('http://hoge.com/user/')
            ->setData(['name' => 'hoge'])
            ->setHeaders(['Accept' => 'application/json'])
            ->setResponse(new TestResponse(new Response(['name' => 'huga'])))
            ->setIsAuthenticated(true)
            ->generateContent();

        $expected = <<< EOS
POST http://hoge.com/user/
Accept: application/json
Authorization: Bearer 

{
    "name": "hoge"
}
# Response:
#{
#    "name": "huga"
#}
EOS;

        $this->assertEquals($expected, $content);
    }

}
