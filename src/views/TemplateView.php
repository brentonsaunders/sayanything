<?php
namespace Views;

class TemplateView extends View {
    public function __construct($file) {
        $this->setContents(file_get_contents($file));
    }
}
