<?php
namespace Views;

abstract class View {
    protected function html() {
        return "";
    }

    public function render() {
        echo $this->html();
    }
}