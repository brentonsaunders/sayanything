<?php
namespace Router;

class Result {
    private $responseCode = null;
    private $response = null;

    private function __construct($responseCode, $response) {
        $this->responseCode = $responseCode;
        $this->response = $response;
    }
    public function getResponseCode() {
        return $this->responseCode;
    }
    public function getResponse() {
        return $this->response;
    }

    public static function ok() {
        return new Result(200, "");
    }

    public static function badRequest() {
        return new Result(400, "");
    }

    public static function notFound() {
        return new Result(404, "");
    }

    public static function json($arr) {
        return new Result(200, json_encode($arr));
    }
}