<?php
namespace Router;

use App;
use Exception;

class Router {
    private App $app;
    private $routes = [];

    public function __construct(App $app) {
        $this->app = $app;
    }

    private function getNumLiteralParts($pattern) {
        $parts = explode("/", $pattern);

        $numParts = 0;

        foreach($parts as $part) {
            if(strlen($part) === 0) {
                continue;
            }

            if(preg_match('/^{[a-zA-Z_$][a-zA-Z_$0-9]*}$/', $part) !== 1) {
                ++$numParts;
            }
        }

        return $numParts;
    }

    public function route($path, $params = [], $method = "GET") {
        if($path === null) {
            $path = "";
        }

        $controllerAndAction = $this->getControllerAndAction($path, $params,
            $method);

        if($controllerAndAction === null) {
            return Result::notFound();
        }

        $controllerClass = $controllerAndAction["controller"];
        $action = $controllerAndAction["action"];
        $params = $controllerAndAction["params"];

        $controller = new $controllerClass();

        $request = new Request($this->app, $this, $method,
            $params);

        $result = call_user_func_array([$controller, $action], [$request]);

        return $result;
    }
    
    private function getControllerAndAction($path, $params, $method) {
        $pathParts = explode("/", $path);

        $routes = $this->routes;

        // Sort the routes by the number of literal parts in the pattern
        usort($routes, function($a, $b) {
            $numPartsA = $this->getNumLiteralParts($a["pattern"]);
            $numPartsB = $this->getNumLiteralParts($b["pattern"]);

            return $numPartsB - $numPartsA;
        });

        foreach($routes as $route) {
            $methods = $route["methods"];
            $pattern = $route["pattern"];
            $referer = $route["referer"];

            $hasMethod = in_array(strtoupper($method), $methods);

            if(!$hasMethod) {
                continue;
            }

            if($referer) {
                if(!isset($_SERVER["HTTP_REFERER"]) || $_SERVER["HTTP_REFERER"] !== $referer) {
                    continue;
                }
            }

            if($pattern === "/") {
                if (empty($url)) {
                    return [
                        "controller" => $route["controller"],
                        "action" => $route["action"],
                        "params" => []
                    ];
                }

                continue;
            }

            $pattern = substr($pattern, 1);

            $patternParts = explode("/", $pattern);

            if(count($patternParts) !== count($pathParts)) {
                continue;
            }

            $numParts = count($patternParts);

            $match = true;

            $pathParams = [];

            for($i = 0; $match && $i < $numParts; ++$i) {
                $firstChar = $patternParts[$i][0];
                $lastChar = $patternParts[$i][strlen($patternParts[$i]) - 1];

                if($firstChar === "{" && $lastChar === "}") {
                    $name = substr($patternParts[$i], 1, -1);

                    $pathParams[$name] = $pathParts[$i];
                } else if($pathParts[$i] !== $patternParts[$i]) {
                    $match = false;
                }
            }

            if(!$match) {
                continue;
            }

            return [
                "controller" => $route["controller"],
                "action" => $route["action"],
                "params" => array_merge($params, $pathParams)
            ];
        }

        return null;
    }

    public function map($methods, $pattern, $controller, $action, $referer = null) {
        if(!is_array($methods) || count($methods) === 0) {
            throw new RouterException("An nonempty array of HTTP methods must be provided for a route.");
        }

        $methods = array_map("strtoupper", $methods);

        foreach ($methods as $method) {
            if (!in_array($method, ["GET", "POST", "PUT", "DELETE"])) {
                throw new RouterException("Route methods must be GET, POST, PUT, or DELETE");
            }
        }

        if($pattern[0] !== "/") {
            throw new RouterException("Route patterns must begin with a slash.");
        }

        if(!class_exists($controller)) {
            throw new RouterException("Nonexistent controller class given for route.");
        }

        if(!method_exists($controller, $action)) {
            throw new RouterException("$controller doesn't have method $action for route.");
        }

        if($referer && filter_var($referer, FILTER_VALIDATE_URL) === false) {
            throw new RouterException("Referrer must be a valid url for route.");
        }

        $this->routes[] = [
            "methods" => $methods,
            "pattern" => $pattern,
            "controller" => $controller,
            "action" => $action,
            "referer" => $referer
        ];
    }
}

class RouterException extends Exception
{
}