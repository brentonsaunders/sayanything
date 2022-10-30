<?php
namespace Controllers;

use App;

abstract class Controller {
    private App $app;
    private $requestMethod = null;
    private $params = null;
    private $router = null;

    public function __construct(App $app) {
        $this->app = $app;
    }

    public function setRequestMethod($requestMethod) {
        $this->requestMethod = $requestMethod;
    }

    public function getRequestMethod() {
        return $this->requestMethod;
    }

    public function setParams($params) {
        $this->params = $params;
    }

    public function getParams() {
        return $this->params;
    }

    public function setRouter($router) {
        $this->router = $router;
    }

    public function getRouter() {
        return $this->router;
    }

    protected function badRequest() {
        http_response_code(400);

        exit;
    }

    protected function jsonResponse($value) {
        header("Content-Type: application/json");

        echo json_encode($value);

        exit;
    }
}