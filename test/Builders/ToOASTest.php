<?php
declare(strict_types=1);

namespace Test\ApiSpec\Builders;

use ApiSpec\Builders\ToOAS;
use Illuminate\Routing\Route;
use Illuminate\Testing\TestResponse;
use Illuminate\Http\Response;
use PHPUnit\Framework\TestCase;
use Test\ApiSpec\MockUser;

class ToOASTest extends TestCase
{
    /**
     * @test
     */
    public function TestGenerateContent_GET()
    {
        $route = new Route("GET", "/user/1",[]);
        $content = (new ToOAS())->setMethod('GET')
            ->setRoute($route)
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
                "operationId": "\/user\/1:GET",
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
        $route = new Route("POST","/user/",[]);
        $content = (new ToOAS())->setMethod('POST')
            ->setRoute($route)
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
        "\/user": {
            "post": {
                "summary": "\/user",
                "description": "\/user",
                "operationId": "\/user:POST",
                "security": [
                    {
                        "bearerAuth": []
                    }
                ],
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
                }
            }
        }
    },
    "components": {
        "securitySchemes": {
            "bearerAuth": {
                "type": "http",
                "scheme": "bearer",
                "bearerFormat": "JWT"
            }
        }
    }
}
EOS;

        $this->assertEquals(json_decode($expected), json_decode($content));
    }

    public function testAggregateContent()
    {
        $builder = new ToOAS();
        $contents = [
            <<< EOS
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
EOS ,
            <<< EOS
{
    "openapi": "3.0.0",
    "info": {
        "title": "auto generated spec",
        "version": "0.0.0"
    },
    "paths": {
        "\/user\/1": {
            "post": {
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
EOS
        ];

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
            },
            "post": {
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
        $this->assertEquals(json_decode($expected), json_decode($builder->aggregateContent($contents)));
    }
}
