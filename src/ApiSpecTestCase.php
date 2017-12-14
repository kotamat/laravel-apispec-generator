<?php

namespace ApiSpec;

use Illuminate\Foundation\Testing\TestCase;

abstract class ApiSpecTestCase extends TestCase
{
    public function postJson($uri, array $data = [], array $headers = [])
    {
        $res = parent::postJson($uri, $data, $headers);

        (new ApiSpecObject())->setMethod('POST')
            ->setUri($uri)
            ->setData($data)
            ->setHeaders($headers)
            ->setResponse($res)
            ->setIsAuthenticated($this->isAuthenticated())
            ->output();

        return $res;
    }

    public function getJson($uri, array $headers = [])
    {
        $res = parent::getJson($uri, $headers);
        (new ApiSpecObject())->setMethod('GET')
            ->setUri($uri)
            ->setHeaders($headers)
            ->setResponse($res)
            ->setIsAuthenticated($this->isAuthenticated())
            ->output();

        return $res;
    }

    public function putJson($uri, array $data = [], array $headers = [])
    {
        $res = parent::putJson($uri, $data, $headers);

        (new ApiSpecObject())->setMethod('PUT')
            ->setUri($uri)
            ->setData($data)
            ->setHeaders($headers)
            ->setResponse($res)
            ->setIsAuthenticated($this->isAuthenticated())
            ->output();

        return $res;
    }

    public function deleteJson($uri, array $data = [], array $headers = [])
    {
        $res = parent::delete($uri, $data, $headers);

        (new ApiSpecObject())->setMethod('DELETE')
            ->setUri($uri)
            ->setData($data)
            ->setHeaders($headers)
            ->setResponse($res)
            ->setIsAuthenticated($this->isAuthenticated())
            ->output();

        return $res;
    }
}
