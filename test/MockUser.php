<?php

namespace Test\ApiSpec;

class MockUser
{
    public function createToken()
    {
        return (object)[
            'accessToken' => 'token',
        ];
    }
}
