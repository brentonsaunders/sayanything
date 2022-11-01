<?php
namespace Views;

class TemplateView extends View {
    private $contents = null;

    public function __construct($file) {
        $this->contents = file_get_contents($file);
    }

    public function html() {
        echo $this->contents;
    }
}
