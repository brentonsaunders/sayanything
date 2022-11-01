<?php
namespace Views;

class MainView implements View {
    private $contents = "";

    public function __construct() {

    }

    protected function setContents($contents) {
        $this->contents = $contents;
    }

    protected function getContents() {
        return $this->contents;
    }

    protected function head() {
        echo "<head>\n";
        echo "<title>Say Anything</title>\n";
        echo "<meta charset=\"UTF-8\">\n";
        echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n";
        echo "<link rel=\"stylesheet\" href=\"css/index.css\">\n";
        echo "<script src=\"js/jquery-3.6.1.min.js\"></script>\n";
        echo "<script src=\"js/App.js\"></script>\n";
        echo "<script src=\"js/index.js\"></script>\n";
        echo "</head>\n";
    }

    protected function body() {
        echo "<body>\n";
        echo "<div id=\"app\">\n";

        echo $this->contents;
        
        echo "</div>\n";
        echo "</body>\n";
    }   

    public function render() {
        echo "<!DOCTYPE html>\n";
        echo "<html>\n";

        $this->head();
        $this->body();

        echo "</html>\n";
    }
}
