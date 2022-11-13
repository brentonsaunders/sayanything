<?php
namespace Controllers;

use App;

abstract class Controller {
    private App $app;
    private $requestMethod = null;
    private $params = null;
    private $router = null;

    public function __construct(App $app, $requestMethod, $postData) {
        $this->app = $app;
        $this->requestMethod = $requestMethod;
        $this->setPostData($postData);
    }

    public function setRequestMethod($requestMethod) {
        $this->requestMethod = $requestMethod;
    }

    public function getRequestMethod() {
        return $this->requestMethod;
    }

    public function setRouter($router) {
        $this->router = $router;
    }

    public function getRouter() {
        return $this->router;
    }

    public function setPostData($postData) {
        $this->postData = $postData;
    }

    public function getRawPostData() {
        return $this->postData;
    }

    public function getPostData() {
        return filter_var_array($this->postData, FILTER_SANITIZE_STRING);
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

    protected function redirect($url) {
        header("Location: $url");

        exit;
    }
}