<?php
namespace Router;

use App;

class Request {
    private App $app;
    private Router $router;
    private $method;
    private $params;

    public function __construct(App $app, Router $router, $method, $params) {
        $this->app = $app;
        $this->router = $router;
        $this->method = $method;
        $this->params = $params;
    }

    public function getApp() : App {
        return $this->app;
    }

    public function getRouter() : Router {
        return $this->router;
    }

    public function getMethod() {
        return $this->method;
    }

    public function getParams() {
        return filter_var_array($this->params, FILTER_SANITIZE_STRING);
    }

    public function getRawParams() {
        return $this->params;
    }
}