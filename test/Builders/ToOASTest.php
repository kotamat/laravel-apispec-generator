<?php

namespace Test\ApiSpec;

use ApiSpec\Builders\ToOAS;
use Illuminate\Testing\TestResponse;
use Illuminate\Http\Response;
use PHPUnit\Framework\TestCase;

class ToOASTest extends TestCase
{
    /**
     * @test
     */
    public function TestGenerateContent_GET()
    {
        $content = (new ToOAS())->setMethod('GET')
            ->setUri('http://hoge.com/user/1')
            ->setResponse(new TestResponse(new Response(['name' => 'huga'])))
            ->generateContent();

        $expected = <<< EOS
{
    "openapi": "3.0.0",
    "info": {
        "title": "auto generated spec",
        "version": "0.0.0"
    },
    "paths": {
        "\/user\/1": {
            "get": {
                "summary": "\/user\/1",
                "description": "\/user\/1",
                "operationId": "\/user\/1",
                "security": [],
                "responses": {
                    "200": {
                        "description": "",
                        "content": {
                            "application\/json": {
                                "schema": {
                                    "required": [
                                        "name"
                                    ],
                                    "properties": {
                                        "name": {
                                            "type": "string",
                                            "example": "huga"
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
EOS;

        $this->assertEquals(json_decode($expected), json_decode($content));
    }

    /**
     * @test
     */
    public function TestGenerateContent_WithData()
    {
        $content = (new ToOAS())->setMethod('POST')
            ->setUri('http://hoge.com/user/')
            ->setData(['name' => 'hoge'])
            ->setHeaders(['Accept' => 'application/json'])
            ->setResponse(new TestResponse(new Response(['name' => 'huga'])))
            ->setAuthenticatedUser(new MockUser())
            ->generateContent();

        $expected = <<< EOS
{
    "openapi": "3.0.0",
    "info": {
        "title": "auto generated spec",
        "version": "0.0.0"
    },
    "paths": {
        "\/user\/": {
            "post": {
                "summary": "\/user\/",
                "description": "\/user\/",
                "operationId": "\/user\/",
                "security": {
                    "bearerAuth": []
                },
                "requestBody": {
                    "content": {
                        "application\/json": {
                            "schema": {
                                "required": [
                                    "name"
                                ],
                                "properties": {
                                    "name": {
                                        "type": "string",
                                        "example": "hoge"
                                    }
                                }
                            }
                        }
                    }
                },
                "responses": {
                    "200": {
                        "description": "",
                        "content": {
                            "application\/json": {
                                "schema": {
                                    "required": [
                                        "name"
                                    ],
                                    "properties": {
                                        "name": {
                                            "type": "string",
                                            "example": "huga"
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    },
    "components": {
        "securitySchemes": {
            "bearerAuth": {
                "type": "http",
                "schema": "bearer",
                "bearerFormat": "JWT"
            }
        }
    }
}
EOS;

        $this->assertEquals(json_decode($expected), json_decode($content));
    }
}
