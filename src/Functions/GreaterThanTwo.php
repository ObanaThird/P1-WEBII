<?php

namespace Obana\App\Functions;

class GreaterThanTwo {
    public function greaterThanTwo($data) {
        $name = $data['name'];
        if(strlen($name) > 2) {
            return true;
        } else {
            return false;
        }
    }
}