<?php

namespace ApiSpec;

use Illuminate\Foundation\Testing\TestCase;

abstract class ApiSpecTestCase extends TestCase
{
    protected $isExportSpec = false;

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
                ->setIsAuthenticated($this->isAuthenticated())
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
                ->setIsAuthenticated($this->isAuthenticated())
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
                ->setIsAuthenticated($this->isAuthenticated())
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
                ->setIsAuthenticated($this->isAuthenticated())
                ->output();
        }

        return $res;
    }
}
