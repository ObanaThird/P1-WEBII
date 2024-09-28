<?php

namespace Obana\App\Functions;

class ValidateFields {
    public function validateFields($data, $requiredFields) {
        $missingFields = [];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                $missingFields[] = $field;
            }
        }
        return $missingFields;
    }
}