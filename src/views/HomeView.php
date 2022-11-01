<?php
namespace Views;

class HomeView extends TemplateView {
    public function __construct() {
        parent::__construct(ROOT_DIR . "/templates/home.html");
    }
}
