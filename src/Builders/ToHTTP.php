<?php

namespace ApiSpec\Builders;

use ApiSpec\Builders\AbstractBuilder;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Foundation\Application;
use Illuminate\Testing\TestResponse;

class ToHTTP extends AbstractBuilder
{
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
        if ($this->authenticatedUser) {
            // TODO select token protocol
            $content .= "Authorization: Bearer ";
            if (method_exists($this->authenticatedUser, 'createToken')) {
                $token = $this->authenticatedUser->createToken('test token');
                $content .= $token->accessToken ?? '';
            }
            $content .= PHP_EOL;
        }

        $content .= PHP_EOL;

        // Content
        if (!empty($this->data)) {
            $param = \json_encode($this->data, JSON_PRETTY_PRINT);
            $content .= $param . PHP_EOL;
        }

        // Response
        $content .= "# Response:" . PHP_EOL . "#";
        $content .= mb_ereg_replace(
            PHP_EOL,
            PHP_EOL . '#',
            \json_encode($this->response->json(), JSON_PRETTY_PRINT)
        );

        return $content;
    }
}
