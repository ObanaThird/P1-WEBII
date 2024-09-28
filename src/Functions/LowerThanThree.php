<?php

namespace Obana\App\Functions;

class LowerThanThree {
    public function lowerThanThree($data) {
        $name = $data['name'];
        if(strlen($name) > 2) {
            return true;
        } else {
            return false;
        }
    }
}