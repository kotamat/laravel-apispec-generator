<?php

namespace ApiSpec;

use ApiSpec\Builders\BuilderInterface;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Support\Facades\Route;
use Illuminate\Testing\TestResponse;

/**
 * trait to output APISpec
 */
trait ApiSpecOutput
{
    protected bool $isExportSpec = false;
    protected $__authenticatedUser = null;

    /**
     * @param UserContract $user
     * @param null $driver
     */
    public function be(UserContract $user, $driver = null)
    {
        parent::be(...func_get_args());
        $this->__authenticatedUser = $user;
    }

    public function json($method, $uri, array $data = [], array $headers = [])
    {
        $res = parent::json(...func_get_args());
        $route = Route::current();
        $this->outputSpec($uri, $route, $data, $headers, $res, $method);

        return $res;
    }

    /**
     * output spec file.
     *
     * @param string $uri request uri
     * @param \Illuminate\Routing\Route|null $route request route
     * @param array $data request body
     * @param array $headers request headers
     * @param TestResponse $response response object
     * @param string $method method name
     * @return void
     */
    protected function outputSpec(
        $uri,
        $route,
        array $data = [],
        array $headers = [],
        TestResponse $response,
        string $method
    )
    {
        if($this->isExportSpec) {
            /** @var BuilderInterface $builder */
            $builder = $this->app->make(BuilderInterface::class);
            $builder?->setApp($this->app)
                ->setMethod($method)
                ->setUri($uri)
                ->setRoute($route)
                ->setData($data)
                ->setHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
                ->setHeaders($headers)
                ->setResponse($response)
                ->setAuthenticatedUser($this->__authenticatedUser)
                ->output();

        }
    }
}
