<?php

namespace ApiSpec\Builders;

class ToOAS extends AbstractBuilder
{
    public function output()
    {
        $content = $this->generateContent();

        $path = preg_replace('/https?:\/\/[0-9\.:a-zA-Z]+\//', '', $this->uri);
        $this->saveOutput($path . '/' . $this->method . '.json', $content);
    }

    private function getType($data)
    {
        $type = gettype($data);
        if ($type === "array") {
            if (array_values($data) === $data) {
                $type = "array";
            } else {
                $type = "object";
            }
        }

        return $type;
    }

    public function buildSwaggerObject(array $data)
    {
        $keys = array_keys($data);
        $op = [
            'required' => $keys,
            'properties' => [],
        ];
        foreach ($data as $k => $d) {
            $type = $this->getType($d);
            if (!in_array($type, ["array", "object", "NULL"])) {
                $op['properties'][$k] = [
                    "type" => $type,
                    "example" => $d,
                ];
            } else {
                switch ($type) {
                    case "array":
                        $childType = $this->getType($d[0]);
                        if ($childType === 'object') {
                            $op['properties'][$k] = [
                                'type' => 'array',
                                'items' => [
                                    'type' => $childType,
                                    'properties' => $this->buildSwaggerObject($d[0])['properties'],
                                ],
                            ];
                            break;
                        }
                        $op['properties'][$k] = [
                            'type' => 'array',
                            'items' => [
                                'type' => $childType,
                            ],
                            'example' => $d,
                        ];
                        break;
                    case 'object' :
                        $op['properties'][$k] = $this->buildSwaggerObject($d);
                        $op['properties'][$k]['type'] = 'object';
                        break;
                    default:
                        break;
                }
            }
        }

        return $op;
    }

    public function generateContent()
    {
        $path = preg_replace('/https?:\/\/[0-9\.:a-zA-Z]+\//', '/', $this->uri);
        $content = [
            'openapi' => '3.0.0',
            'info' => [
                'title' => "auto generated spec",
                'version' => "0.0.0"
            ],
            'paths' => [
                $path => [
                    strtolower($this->method) => [
                        "summary" => $path,
                        "description" => $path,
                        "operationId" => $path,
                        "security" => $this->authenticatedUser ? [[
                            "bearerAuth" => []
                        ]]: [],
                        "responses" => [
                            200 => [
                                "description" => "",
                                "content" => [
                                    "application/json" => [
                                        "schema" => $this->buildSwaggerObject($this->response->json()),
                                    ]
                                ]
                            ],
                        ],
                    ],
                ],
            ],
        ];
        if($this->data) {
            $content['paths'][$path][strtolower($this->method)]['requestBody'] = [
                "content" => [
                    "application/json" => [
                        "schema" => $this->buildSwaggerObject($this->data),
                    ]
                ]
            ];
        }
        if ($this->authenticatedUser) {
            $content['components'] = [
                "securitySchemes" => [
                    "bearerAuth" => [
                        "type" => "http",
                        "scheme" => "bearer",
                        "bearerFormat" => "JWT"
                    ]
                ]
            ];
        }

        return json_encode($content, JSON_PRETTY_PRINT);
    }
}