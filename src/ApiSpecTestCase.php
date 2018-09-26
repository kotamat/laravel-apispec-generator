<?php

namespace ApiSpec;

use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Foundation\Testing\TestCase;

abstract class ApiSpecTestCase extends TestCase
{
    protected $isExportSpec = false;
    protected $__loginUser = null;

    public function be(UserContract $user, $driver = null)
    {
        parent::be(...func_get_args());
        $this->__loginUser = $user;
    }

    public function postJson($uri, array $data = [], array $headers = [])
    {
        $res = parent::postJson($uri, $data, $headers);

        if ($this->isExportSpec) {
            (new ApiSpecObject())->setApp($this->app)
                ->setMethod('POST')
                ->setUri($uri)
                ->setData($data)
                ->setHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
                ->setHeaders($headers)
                ->setResponse($res)
                ->setAuthenticatedUser($this->__loginUser)
                ->output();
        }

        return $res;
    }

    public function getJson($uri, array $headers = [])
    {
        $res = parent::getJson($uri, $headers);

        if ($this->isExportSpec) {
            (new ApiSpecObject())->setApp($this->app)
                ->setMethod('GET')
                ->setUri($uri)
                ->setHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
                ->setHeaders($headers)
                ->setResponse($res)
                ->setAuthenticatedUser($this->__loginUser)
                ->output();
        }

        return $res;
    }

    public function putJson($uri, array $data = [], array $headers = [])
    {
        $res = parent::putJson($uri, $data, $headers);

        if ($this->isExportSpec) {
            (new ApiSpecObject())->setApp($this->app)
                ->setMethod('PUT')
                ->setUri($uri)
                ->setData($data)
                ->setHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
                ->setHeaders($headers)
                ->setResponse($res)
                ->setAuthenticatedUser($this->__loginUser)
                ->output();
        }

        return $res;
    }

    public function deleteJson($uri, array $data = [], array $headers = [])
    {
        $res = parent::delete($uri, $data, $headers);

        if ($this->isExportSpec) {
            (new ApiSpecObject())->setApp($this->app)
                ->setMethod('DELETE')
                ->setUri($uri)
                ->setData($data)
                ->setHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
                ->setHeaders($headers)
                ->setResponse($res)
                ->setAuthenticatedUser($this->__loginUser)
                ->output();
        }

        return $res;
    }
}
