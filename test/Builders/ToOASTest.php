<?php
declare(strict_types=1);

namespace Test\ApiSpec\Builders;

use ApiSpec\Builders\ToOAS;
use Illuminate\Routing\Route;
use Illuminate\Testing\TestResponse;
use Illuminate\Http\Response;
use PHPUnit\Framework\TestCase;
use Test\ApiSpec\MockUser;

class ToOASTest extends TestCase
{
    /**
     * @test
     */
    public function TestGenerateContent_GET()
    {
        $route = new Route("GET", "/user/1", []);
        $content = (new ToOAS())->setMethod('GET')
            ->setRoute($route)
            ->setUri("http://localhost/user/1?hoge=aaa&fuga=bbb")
            ->setHeaders(["X-User-Id" => 1])
            ->setResponse(new TestResponse(new Response(['name' => 'huga'])))
            ->generateContent();

        $expected = file_get_contents(__DIR__ . "/data/ToOASTest/" . __FUNCTION__ . ".expected.json");

        $this->assertEquals(json_decode($expected), json_decode($content));
    }

    /**
     * @test
     */
    public function TestGenerateContent_WithData()
    {
        $route = new Route("POST", "/user/", []);
        $content = (new ToOAS())->setMethod('POST')
            ->setRoute($route)
            ->setData(['name' => 'hoge'])
            ->setHeaders(['Accept' => 'application/json'])
            ->setResponse(new TestResponse(new Response(['name' => 'huga'])))
            ->setAuthenticatedUser(new MockUser())
            ->generateContent();

        $expected = file_get_contents(__DIR__ . "/data/ToOASTest/" . __FUNCTION__ . ".expected.json");

        $this->assertEquals(json_decode($expected), json_decode($content));
    }

    public function testAggregateContent()
    {
        $builder = new ToOAS();

        $input = [
            file_get_contents(__DIR__ . "/data/ToOASTest/" . __FUNCTION__ . ".input1.json"),
            file_get_contents(__DIR__ . "/data/ToOASTest/" . __FUNCTION__ . ".input2.json"),
        ];
        $content = $builder->aggregateContent($input);

        $expected = file_get_contents(__DIR__ . "/data/ToOASTest/" . __FUNCTION__ . ".expected.json");

        $this->assertEquals(json_decode($expected), json_decode($content));
    }
}
