<?php

namespace ApiSpec\Builders;

use Illuminate\Foundation\Application;
use Illuminate\Testing\TestResponse;

abstract class AbstractBuilder implements BuilderInterface
{
    protected string $method = "";
    protected string $uri = "";
    protected array $headers = [];
    protected TestResponse $response;
    protected array $data = [];
    protected $authenticatedUser = null;
    protected Application $app;
    public function saveOutput(string $filename, string $content)
    {
        $this->app['filesystem']->drive('local')->put($filename, $content);
    }
    //////////////////////
    // setters
    //////////////////////
    /**
     * @param string $method
     *
     * @return BuilderInterface
     */
    public function setMethod(string $method): BuilderInterface
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @param string $uri
     *
     * @return BuilderInterface
     */
    public function setUri(string $uri): BuilderInterface
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * @param array $headers
     *
     * @return BuilderInterface
     */
    public function setHeaders(array $headers): BuilderInterface
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    /**
     * @param TestResponse $response
     *
     * @return BuilderInterface
     */
    public function setResponse(TestResponse $response): BuilderInterface
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @param array $data
     *
     * @return BuilderInterface
     */
    public function setData(array $data): BuilderInterface
    {
        $this->data = $data;

        return $this;
    }

    public function setAuthenticatedUser($authenticatedUser): BuilderInterface
    {
        $this->authenticatedUser = $authenticatedUser;

        return $this;
    }

    public function setApp(Application $app)
    {
        $this->app = $app;

        return $this;
    }

}