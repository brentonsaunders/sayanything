<?php
namespace Controllers;

abstract class Controller {
    private $requestMethod = null;
    private $params = null;

    public function __construct() {
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