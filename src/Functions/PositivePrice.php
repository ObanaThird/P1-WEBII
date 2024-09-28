<?php

namespace Obana\App\Functions;

class PositivePrice {
    public function positivePrice($data) {
        $price = $data['price'];
        if($price > 0) {
            return true;
        } else {
            return false;
        }
    }
}