<?php
declare(strict_types=1);

namespace ApiSpec\Builders;

use Illuminate\Foundation\Application;
use Illuminate\Routing\Route;
use Illuminate\Testing\TestResponse;

abstract class AbstractBuilder implements BuilderInterface
{
    protected string $method = "";
    protected string $uri = "";
    protected Route $route;
    protected array $headers = [];
    protected TestResponse $response;
    protected array $data = [];
    protected $authenticatedUser = null;
    protected Application $app;

    public function saveOutput(string $filename, string $content)
    {
        $this->app['filesystem']->drive('local')->put($filename, $content);
    }

    public function loadOutputs(string $dir, string $pattern): array
    {
        $allFiles = $this->app['filesystem']->drive('local')->allFiles($dir);
        $contents = [];
        foreach ($allFiles as $file) {
            if (preg_match($pattern, $file)) {
                $contents[] = $this->app['filesystem']->drive('local')->get($file);
            }
        }
        return $contents;
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
     * @param Route $route
     *
     * @return BuilderInterface
     */
    public function setRoute(Route $route): BuilderInterface
    {
        $this->route=$route;

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

    public function setApp(Application $app): BuilderInterface
    {
        $this->app = $app;

        return $this;
    }

}
