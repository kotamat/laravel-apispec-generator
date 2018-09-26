<?php

namespace ApiSpec;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestResponse;

/**
 * @property string       method
 * @property string       uri
 * @property array        headers
 * @property TestResponse response
 * @property array        data
 * @property bool         isAuthenticated
 * @property Application  app
 */
class ApiSpecObject
{
    protected $method    = [];
    protected $uri       = [];
    protected $headers   = [];
    protected $response  = null;
    protected $data      = [];
    protected $loginUser = null;
    protected $app;

    public function output()
    {
        $content = $this->generateContent();

        $path = preg_replace('/https?:\/\/[0-9\.:a-zA-Z]+\//', '', $this->uri);
        $this->saveOutput($path . '/' . $this->method . '.http', $content);
    }

    public function generateContent()
    {
        // Uri
        $content = "$this->method $this->uri" . PHP_EOL;

        // Header
        foreach ($this->headers as $key => $value) {
            $content .= "$key: $value" . PHP_EOL;
        }
        if ($this->loginUser) {
            // TODO select token protocol
            $content .= "Authorization: Bearer ";
            if (method_exists($this->loginUser, 'createToken')) {
                $token   = $this->loginUser->createToken('test token');
                $content .= $token->accessToken ?? '';
            }
            $content .= PHP_EOL;
        }

        $content .= PHP_EOL;

        // Content
        if (!empty($this->data)) {
            $param   = \json_encode($this->data, JSON_PRETTY_PRINT);
            $content .= $param . PHP_EOL;
        }

        // Response
        $content .= "# Response:" . PHP_EOL . "#";
        $content .= mb_ereg_replace(
            PHP_EOL,
            PHP_EOL . '#',
            \json_encode($this->response->json(), JSON_PRETTY_PRINT));

        return $content;
    }

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
     * @return ApiSpecObject
     */
    public function setMethod(string $method): ApiSpecObject
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @param string $uri
     *
     * @return ApiSpecObject
     */
    public function setUri(string $uri): ApiSpecObject
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * @param array $headers
     *
     * @return ApiSpecObject
     */
    public function setHeaders(array $headers): ApiSpecObject
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    /**
     * @param TestResponse $response
     *
     * @return ApiSpecObject
     */
    public function setResponse(TestResponse $response): ApiSpecObject
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @param array $data
     *
     * @return ApiSpecObject
     */
    public function setData(array $data): ApiSpecObject
    {
        $this->data = $data;

        return $this;
    }

    public function setAuthenticatedUser($loginUser): ApiSpecObject
    {
        $this->loginUser = $loginUser;

        return $this;
    }

    public function setApp(Application $app)
    {
        $this->app = $app;

        return $this;
    }
}
