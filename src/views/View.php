<?php
namespace Views;

use Router\Result;

abstract class View extends Result {
    public function getResponseCode() { return 200; }
    public function getResponse() { return $this->render(); }
    protected function render() { return ""; }
}