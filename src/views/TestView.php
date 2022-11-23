<?php
namespace Views;

class TestView extends MainView {
    private View $testGameView;

    public function __construct(View $testGameView) {
        $this->testGameView = $testGameView;
    }

    protected function main() {
        $this->testGameView->render();
    }
}
