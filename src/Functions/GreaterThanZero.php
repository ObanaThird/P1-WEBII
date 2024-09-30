<?php

namespace Obana\App\Functions;

class GreaterThanZero {
    public function greaterThanZero($data) {
        $storage = $data['storage'];
        if($storage < 0 || is_float($storage) || is_string($storage)) {
            return false;
        } else {
            return true;
        }
    }
}