<?php

namespace Obana\App\Functions;

class PositivePrice {
    public function positivePrice($data) {
        $price = $data['price'];
        if($price > 0 && !is_string($price)) {
            return true;
        } else {
            return false;
        }
    }
}