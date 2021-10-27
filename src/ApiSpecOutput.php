<?php

namespace ApiSpec;

use ApiSpec\Builders\BuilderInterface;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Testing\TestResponse;

/**
 * trait to output APISpec
 */
trait ApiSpecOutput
{
    protected BuilderInterface|null $builder = null;
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
        $this->outputSpec($uri, $data, $headers, $res, $method);

        return $res;
    }

    /**
     * output spec file.
     *
     * @param string $uri request uri
     * @param array $data request body
     * @param array $headers request headers
     * @param TestResponse $response response object
     * @param string $method method name
     * @return void
     */
    protected function outputSpec(
        $uri,
        array $data = [],
        array $headers = [],
        TestResponse $response,
        string $method
    )
    {
        $this->builder?->setApp($this->app)
            ->setMethod($method)
            ->setUri($uri)
            ->setData($data)
            ->setHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
            ->setHeaders($headers)
            ->setResponse($response)
            ->setAuthenticatedUser($this->__authenticatedUser)
            ->output();
    }
}
