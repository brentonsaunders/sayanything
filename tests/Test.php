<?php
class Test {
    public function __construct() {
        $methods = get_class_methods($this);

        $className = get_class($this);

        echo "Running $className ...<br>";

        foreach($methods as $method) {
            if(preg_match("/^[A-Z]+_[A-Z]+(_[A-Z]+)*$/i", $method) !== 1) {
                continue;
            }

            echo "Running $method ... ";

            $result = $this->$method();

            if($result) {
                echo '<span style="color: rgb(0, 255, 0)">PASSED</span>';
            } else {
                echo '<span style="color: rgb(255, 0, 0)">FAILED</span>';
            }

            echo "<br>";
        }

        echo "Finished running $className.<br>";
    }
}
