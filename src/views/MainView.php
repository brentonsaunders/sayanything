<?php
namespace Views;

class MainView implements View {
    public function __construct() {

    }

    public function render() {
        echo <<<EOD
<!DOCTYPE html>
<html>
    <head>
        <title>Say Anything</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/index.css">
        <script src="js/jquery-3.6.1.min.js"></script>
        <script src="js/index.js"></script>
    </head>
    <body>
        <div id="app">
            <header>
                <div class="left">
                    <div id="menu">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </div>
                <div class="center"><div id="title">Say Anything</div></div>
                <div class="right"></div>
            </header>
            <main>
                
            </main>
        </div>
    </body>
</html>
        
EOD;
    }
}