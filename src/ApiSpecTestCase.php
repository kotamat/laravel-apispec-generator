<?php
declare(strict_types=1);

namespace ApiSpec;

use Illuminate\Foundation\Testing\TestCase;

abstract class ApiSpecTestCase extends TestCase
{
    use ApiSpecOutput;
}
