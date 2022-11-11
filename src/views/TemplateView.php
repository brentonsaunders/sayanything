<?php
namespace Views;

class TemplateView implements View {
    private $contents = null;

    public function __construct($file) {
        $this->contents = file_get_contents($file);
    }

    public function render() {
        echo $this->contents;
    }
}
