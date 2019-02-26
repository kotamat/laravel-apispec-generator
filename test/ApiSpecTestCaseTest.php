<?php
namespace Test\ApiSpec;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Application;
use ApiSpec\ApiSpecTestCase;
use Illuminate\Foundation\Testing\Concerns\MocksApplicationServices;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use Illuminate\Filesystem\FilesystemAdapter;
use Mockery as m;

/**
 * Confirm ApiSpecTestCase output spec file
 */
class ApiSpecTestCaseTest extends ApiSpecTestCase
{

    use MocksApplicationServices;

    private $acceptor;

    public function createApplication()
    {
        $app = new class extends Application {
            private $acceptor;

            public function __construct($basePath = null)
            {
                //
            }

            public function setAcceptor($acceptor)
            {
                $this->acceptor = $acceptor;
            }

            public function make($class, array $param = []) {
                $mock = m::mock(FilesystemAdapter::class);
                $mock->shouldReceive('drive')->andReturn($this->acceptor);
                return $mock;
            }
        };

        $app->setAcceptor($this->acceptor);

        $this->app = $app;
    }

    protected function setUp()
    {
        $this->acceptor = new class {
            public $filename;
            public $str;
            public function put($filename, $str) {
                $this->filename = $filename;
                $this->str = $str;
            }
        };
        $this->createApplication();
    }

    /**
     * @test
     * @dataProvider provideJsonRequests
     */
    public function canOutputHttpSpec($method, $url, $expectedFilename)
    {
        $this->isExportSpec = true;
        $methodName = strtoupper(str_replace('Json', '', $method));

        $this->{$method}($url);

        // output file correspondented to url structure
        $this->assertEquals($expectedFilename, $this->acceptor->filename);
        $expectedStr = <<< EOS
$methodName $url
Content-Type: application/json
Accept: application/json

# Response:
#{
#    "name": "huga",
#    "rank": "foo"
#}
EOS;
        // .http str
        $this->assertEquals($expectedStr, $this->acceptor->str);

    }

    public function json($method, $uri, array $data = [], array $headers = [])
    {
        return new TestResponse(new Response(['name' => 'huga', 'rank' => 'foo']));
    }

    public function provideJsonRequests()
    {
        return [
            [
                'getJson',
                'foo/bar',
                'foo/bar/GET.http'
            ],
            [
                'postJson',
                'jazz/rock/progressive',
                'jazz/rock/progressive/POST.http'
            ],
            [
                'putJson',
                'foo/bar',
                'foo/bar/PUT.http'
            ],
            [
                'deleteJson',
                'jazz/rock/progressive',
                'jazz/rock/progressive/DELETE.http'
            ],
        ];
    }
}
