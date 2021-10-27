<?php

namespace ApiSpec\Builders;

use Illuminate\Foundation\Application;
use Illuminate\Testing\TestResponse;

interface BuilderInterface
{
    public function output();

    public function generateContent();

    public function saveOutput(string $filename, string $content);

    //////////////////////
    // setters
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


    public function setAuthenticatedUser($authenticatedUser): BuilderInterface;


    public function setApp(Application $app);

}
