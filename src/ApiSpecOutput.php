<?php
namespace ApiSpec;

use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Foundation\Testing\TestCase;

trait ApiSpecOutput
{
    protected $isExportSpec = false;
    protected $__authenticatedUser = null;

    /**
     * @param UserContract $user
     * @param null         $driver
     */
    public function be(UserContract $user, $driver = null)
    {
        parent::be(...func_get_args());
        $this->__authenticatedUser = $user;
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
                ->setAuthenticatedUser($this->__authenticatedUser)
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
                ->setAuthenticatedUser($this->__authenticatedUser)
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
                ->setAuthenticatedUser($this->__authenticatedUser)
                ->output();
        }

        return $res;
    }

    public function deleteJson($uri, array $data = [], array $headers = [])
    {
        $res = parent::deleteJson($uri, $data, $headers);

        if ($this->isExportSpec) {
            (new ApiSpecObject())->setApp($this->app)
                ->setMethod('DELETE')
                ->setUri($uri)
                ->setData($data)
                ->setHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
                ->setHeaders($headers)
                ->setResponse($res)
                ->setAuthenticatedUser($this->__authenticatedUser)
                ->output();
        }

        return $res;
    }
}