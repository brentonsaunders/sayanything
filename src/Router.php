<?php
class Router {
    private App $app;

    public function __construct(App $app) {
        $this->app = $app;
    }

    public function route($request) {
        $url = $request['url'] ?? null;

        unset($request['url']);

        $params = $request;

        $controllerAndAction = $this->getControllerAndAction($url);

        $controller = $controllerAndAction['controller'];
        $action = $controllerAndAction['action'];

        $controller->setRequestMethod($_SERVER["REQUEST_METHOD"]);

        $controller->setParams($params);

        $controller->setRouter($this);

        $controller->$action();
    }

    private function getControllerAndAction($url) {
        if(!$url) {
            $controller = "Controllers\\DefaultController";
            $action = "index";
        } else {
            $parts = explode('/', $url);

            if(count($parts) === 2) {
                $controller = "Controllers\\{$parts[0]}Controller";
                $action = $parts[1];
            } else {
                $controller = "Controllers\\{$parts[0]}Controller";
                $action = "index";
            }
        }

        if(!class_exists($controller)) {
            $controller = "Controllers\\DefaultController";
        }

        if(!method_exists($controller, $action)) {
            $action = "index";
        }

        return [
            'controller' => new $controller($this->app),
            'action' => $action
        ];
    }
}