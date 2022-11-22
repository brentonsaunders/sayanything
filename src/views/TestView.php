<?php
namespace Views;

class TestView extends MainView {
    private TestGameView $testGameView;

    public function __construct(TestGameView $testGameView) {
        $this->testGameView = $testGameView;
    }

    protected function main() {
        $this->testGameView->render();
    }
}
