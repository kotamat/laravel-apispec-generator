<?php
declare(strict_types=1);

namespace Test\ApiSpec\Builders;

use ApiSpec\Builders\ToHTTP;
use Illuminate\Testing\TestResponse;
use Illuminate\Http\Response;
use PHPUnit\Framework\TestCase;
use Test\ApiSpec\MockUser;

class ToHTTPTest extends TestCase
{
    /**
     * @test
     */
    public function TestGenerateContent_GET()
    {
        $content = (new ToHTTP())->setMethod('GET')
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
        $content = (new ToHTTP())->setMethod('POST')
            ->setUri('http://hoge.com/user/')
            ->setData(['name' => 'hoge'])
            ->setHeaders(['Accept' => 'application/json'])
            ->setResponse(new TestResponse(new Response(['name' => 'huga'])))
            ->setAuthenticatedUser(new MockUser())
            ->generateContent();

        $expected = <<< EOS
POST http://hoge.com/user/
Accept: application/json
Authorization: Bearer token

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
