<?php
declare(strict_types=1);

namespace ApiSpec\Builders;

use Illuminate\Foundation\Application;
use Illuminate\Routing\Route;
use Illuminate\Testing\TestResponse;

interface BuilderInterface
{
    /**
     * procedure output apispec, after setup
     * @return void
     */
    public function output(): void;

    /**
     * generate apispec content
     *
     * @return string
     */
    public function generateContent(): string;

    /**
     * save content to filename
     *
     * @param string $filename
     * @param string $content
     *
     * @return void
     */
    public function saveOutput(string $filename, string $content);

    /**
     * aggregate and save all output contents
     */
    public function aggregate(): void;

    //////////////////////
    // setup methods
    //////////////////////
    /**
     * @param string $method
     *
     * @return BuilderInterface
     */
    public function setMethod(string $method): BuilderInterface;


    /**
     * @param string $uri
     *
     * @return BuilderInterface
     */
    public function setUri(string $uri): BuilderInterface;

    /**
     * @param Route $route
     *
     * @return BuilderInterface
     */
    public function setRoute(Route $route): BuilderInterface;

    /**
     * @param array $headers
     *
     * @return BuilderInterface
     */
    public function setHeaders(array $headers): BuilderInterface;


    /**
     * @param TestResponse $response
     *
     * @return BuilderInterface
     */
    public function setResponse(TestResponse $response): BuilderInterface;


    /**
     * @param array $data
     *
     * @return BuilderInterface
     */
    public function setData(array $data): BuilderInterface;


    /**
     * @param $authenticatedUser
     *
     * @return BuilderInterface
     */
    public function setAuthenticatedUser($authenticatedUser): BuilderInterface;


    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return BuilderInterface
     */
    public function setApp(Application $app): BuilderInterface;

}
