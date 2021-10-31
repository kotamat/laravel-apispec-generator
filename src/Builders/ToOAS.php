<?php
declare(strict_types=1);

namespace ApiSpec\Builders;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class ToOAS extends AbstractBuilder
{
    public function output(): void
    {
        try {
            $content = $this->generateContent();
        } catch (\Throwable $exception) {
            return;
        }

        $path = $this->route->uri;
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

    public function buildSwaggerObject(array $data): array
    {
        if ($this->getType($data) === "array") {
            return [
                'type'  => 'array',
                'items' => $this->buildSwaggerObject($data[0]),
            ];
        }
        $keys = array_values(array_filter(array_keys($data), fn($a) => is_string($a)));
        $op   = [
            'properties' => [],
        ];
        if (count($keys) > 0) {
            $op['required'] = $keys;
        }
        foreach ($data as $k => $d) {
            $type = $this->getType($d);
            if (!in_array($type, ["array", "object", "NULL"])) {
                $op['properties'][$k] = [
                    "type"    => $type,
                    "example" => $d,
                ];
            } else {
                switch ($type) {
                    case "array":
                        $childType = $this->getType($d[0] ?? "");
                        if ($childType === 'object') {
                            $op['properties'][$k] = [
                                'type'  => 'array',
                                'items' => [
                                    'type'       => $childType,
                                    'properties' => $this->buildSwaggerObject($d[0])['properties'],
                                ],
                            ];
                            break;
                        }
                        $op['properties'][$k] = [
                            'type'    => 'array',
                            'items'   => [
                                'type' => $childType,
                            ],
                            'example' => $d,
                        ];
                        break;
                    case 'object' :
                        $op['properties'][$k]         = $this->buildSwaggerObject($d);
                        $op['properties'][$k]['type'] = 'object';
                        break;
                    default:
                        break;
                }
            }
        }

        return $op;
    }

    public function generateContent(): string
    {
        $symfonyRequest = SymfonyRequest::create($this->uri);
        $path           = "/" . $this->route->uri;
        $content        = [
            'openapi' => '3.0.0',
            'info'    => [
                'title'   => "auto generated spec",
                'version' => "0.0.0",
            ],
            'paths'   => [
                $path => [
                    strtolower($this->method) => [
                        "summary"     => $path,
                        "description" => $path,
                        "operationId" => "$path:$this->method",
                        "security"    => $this->authenticatedUser ? [
                            [
                                "bearerAuth" => [],
                            ],
                        ] : [],
                        "responses"   => [
                            200 => [
                                "description" => "",
                            ],
                        ],
                    ],
                ],
            ],
        ];
        if (!empty($symfonyRequest->query->all())) {
            if (empty($content['paths'][$path][strtolower($this->method)]["parameters"])) {
                $content['paths'][$path][strtolower($this->method)]["parameters"] = [];
            }
            foreach ($symfonyRequest->query->all() as $key => $value) {
                $content['paths'][$path][strtolower($this->method)]["parameters"][] = [
                    "in"          => "query",
                    "name"        => $key,
                    "schema"      => [
                        "type" => $this->getType($value),
                    ],
                    "description" => "$value",
                ];
            }
        }
        if ($this->headers) {
            if (empty($content['paths'][$path][strtolower($this->method)]["parameters"])) {
                $content['paths'][$path][strtolower($this->method)]["parameters"] = [];
            }
            foreach ($this->headers as $key => $value) {
                $content['paths'][$path][strtolower($this->method)]["parameters"][] = [
                    "in"          => "header",
                    "name"        => $key,
                    "schema"      => [
                        "type" => $this->getType($value),
                    ],
                    "description" => "$value",
                ];
            }
        }
        if ($this->route->parameters) {
            if (empty($content['paths'][$path][strtolower($this->method)]["parameters"])) {
                $content['paths'][$path][strtolower($this->method)]["parameters"] = [];
            }
            foreach ($this->route->parameters as $key => $parameter) {
                $param = $parameter;
                if ($parameter instanceof Model) {
                    $param = $parameter->getKey();
                }
                $content['paths'][$path][strtolower($this->method)]["parameters"][] = [
                    "in"          => "path",
                    "name"        => $key,
                    "required"    => true,
                    "schema"      => [
                        "type" => $this->getType($param),
                    ],
                    "description" => "$param",
                ];
            }
        }
        if ($this->response->content() && !empty($this->response->json())) {
            $response = $this->response->json();
            if (is_array($response)) {
                $response = $this->buildSwaggerObject($response);
            }
            $content['paths'][$path][strtolower($this->method)]['responses'][200]["content"] = [
                "application/json" => [
                    "schema" => $response,
                ],
            ];
        }
        if ($this->data) {
            $content['paths'][$path][strtolower($this->method)]['requestBody'] = [
                "content" => [
                    "application/json" => [
                        "schema" => $this->buildSwaggerObject($this->data),
                    ],
                ],
            ];
        }
        if ($this->authenticatedUser) {
            $content['components'] = [
                "securitySchemes" => [
                    "bearerAuth" => [
                        "type"         => "http",
                        "scheme"       => "bearer",
                        "bearerFormat" => "JWT",
                    ],
                ],
            ];
        }

        return json_encode($content, JSON_PRETTY_PRINT);
    }

    public function aggregate(): void
    {
        $contents = $this->loadOutputs('api', '/.*\.json/');

        $aggregated = $this->aggregateContent($contents);
        $this->saveOutput('all.json', $aggregated);
    }

    /**
     * @param array $contents
     *
     * @return string
     */
    public function aggregateContent(array $contents): string
    {
        $aggregated = [];
        foreach ($contents as $contentStr) {
            $content = json_decode($contentStr, true);
            if (empty($aggregated)) {
                $aggregated = $content;
            } else {
                $path = array_key_first($content['paths']);
                if (empty($aggregated['paths'][$path])) {
                    $aggregated['paths'][$path] = $content['paths'][$path];
                } else {
                    $method                              = array_key_first($content['paths'][$path]);
                    $aggregated['paths'][$path][$method] = $content['paths'][$path][$method];
                }
                if (empty($aggregated['components']) && !empty($content['components'])) {
                    $aggregated['components'] = $content['components'] ?? [];
                }
            }
        }

        return json_encode($aggregated);
    }
}
