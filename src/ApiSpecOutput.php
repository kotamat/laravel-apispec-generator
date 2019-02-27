<?php
namespace ApiSpec;

use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Foundation\Testing\TestResponse;

/**
 * trait to output APISpec 
 */
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

        $this->outputSpec($uri, $data, $headers, $res, 'POST');

        return $res;
    }

    public function getJson($uri, array $headers = [])
    {
        $res = parent::getJson($uri, $headers);

        $this->outputSpec($uri, [], $headers, $res, 'GET');

        return $res;
    }

    public function putJson($uri, array $data = [], array $headers = [])
    {
        $res = parent::putJson($uri, $data, $headers);

        $this->outputSpec($uri, $data, $headers, $res, 'PUT');

        return $res;
    }

    public function deleteJson($uri, array $data = [], array $headers = [])
    {
        $res = parent::deleteJson($uri, $data, $headers);

        $this->outputSpec($uri, $data, $headers, $res, 'DELETE');

        return $res;
    }

    public function patchJson($uri, array $data = [], array $headers = [])
    {
        $res = parent::patchJson($uri, $data, $headers);

        $this->outputSpec($uri, $data, $headers, $res, 'PATCH');

        return $res;
    }

    /**
     * output spec file.
     *
     * @param string       $uri      request uri
     * @param array        $data     request body
     * @param array        $headers  request headers
     * @param TestResponse $response response object
     * @param string       $method   method name
     * @return void
     */
    protected function outputSpec(
        $uri,
        array $data = [],
        array $headers = [],
        TestResponse $response,
        string $method
    ) {
        if ($this->isExportSpec) {
            (new ApiSpecObject())->setApp($this->app)
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
}