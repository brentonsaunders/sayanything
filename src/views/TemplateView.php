<?php
namespace Views;

class TemplateView extends MainView {
    public function __construct($file) {
        $this->setContents(file_get_contents($file));
    }
}
