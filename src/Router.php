<?php
class Router {
    private App $app;
    private $routes = [
        [
            "method" => "GET",
            "pattern" => "/",
            "controller" => "Controllers\\DefaultController",
            "action" => "lobby"
        ],
        [
            "method" => "POST",
            "pattern" => "/",
            "controller" => "Controllers\\DefaultController",
            "action" => "create"
        ],
        [
            "method" => "GET",
            "pattern" => "/{gameId}",
            "controller" => "Controllers\\DefaultController",
            "action" => "game"
        ],
        [
            "method" => "GET",
            "pattern" => "/{gameId}/view",
            "controller" => "Controllers\\DefaultController",
            "action" => "view"
        ],
        [
            "method" => "POST",
            "pattern" => "/{gameId}/join",
            "controller" => "Controllers\\DefaultController",
            "action" => "join"
        ],
        [
            "method" => "POST",
            "pattern" => "/{gameId}/start",
            "controller" => "Controllers\\DefaultController",
            "action" => "start"
        ],
        [
            "method" => "POST",
            "pattern" => "/{gameId}/ask",
            "controller" => "Controllers\\DefaultController",
            "action" => "ask"
        ],
        [
            "method" => "POST",
            "pattern" => "/{gameId}/nextRound",
            "controller" => "Controllers\\DefaultController",
            "action" => "nextRound"
        ],
        [
            "method" => "POST",
            "pattern" => "/{gameId}/answer",
            "controller" => "Controllers\\DefaultController",
            "action" => "answer"
        ],
        [
            "method" => "POST",
            "pattern" => "/{gameId}/vote",
            "controller" => "Controllers\\DefaultController",
            "action" => "vote"
        ],
        [
            "method" => "POST",
            "pattern" => "/{gameId}/chooseAnswer",
            "controller" => "Controllers\\DefaultController",
            "action" => "chooseAnswer"
        ],
    ];

    public function __construct(App $app) {
        $this->app = $app;
    }

    public function route($request) {
        $url = $request['url'] ?? null;

        unset($request['url']);

        $urlParts = explode("/", $url);

        foreach($this->routes as $route) {
            $method = $route["method"];
            $pattern = $route["pattern"];

            if(strtolower($method) !== strtolower($_SERVER["REQUEST_METHOD"])) {
                continue;
            }

            if($pattern[0] !== "/") {
                throw new Exception("Route patterns must begin with a slash!");
            }

            if($pattern === "/") {
                if(empty($url)) {
                    $controllerClass = $route["controller"];
                    $action = $route["action"];

                    $controller = new $controllerClass($this->app, $_SERVER["REQUEST_METHOD"],
                        $_POST);

                    $controller->$action();

                    return;
                }

                continue;
            }

            $pattern = substr($pattern, 1);

            $patternParts = explode("/", $pattern);

            if(count($patternParts) !== count($urlParts)) {
                continue;
            }

            $numParts = count($patternParts);

            $match = true;

            $params = [];

            for($i = 0; $match && $i < $numParts; ++$i) {
                $firstChar = $patternParts[$i][0];
                $lastChar = $patternParts[$i][strlen($patternParts[$i]) - 1];

                if($firstChar === "{" && $lastChar === "}") {
                    $name = substr($patternParts[$i], 1, -1);

                    $params[$name] = $urlParts[$i];
                } else if($urlParts[$i] !== $patternParts[$i]) {
                    $match = false;
                }
            }

            if(!$match) {
                continue;
            }

            $controllerClass = $route["controller"];
            $action = $route["action"];

            $controller = new $controllerClass($this->app, $_SERVER["REQUEST_METHOD"],
                $_POST);

            call_user_func_array([$controller, $action], $params);

            return;
        }

        throw new Exception("No routes found for");
    }
}
