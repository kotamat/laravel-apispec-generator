<?php
declare(strict_types=1);

namespace Test\ApiSpec;

class MockUser
{
    public function createToken(): object
    {
        return (object)[
            'accessToken' => 'token',
        ];
    }
}
