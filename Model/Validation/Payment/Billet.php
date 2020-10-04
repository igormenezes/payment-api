<?php

namespace Model\Validation\Payment;

class Billet extends Payment {
    const FIELDS = [
        'full_name',
        'document',
        'amount',
        'type'
    ];

    public function validateFields($fields) {
        foreach($fields as $key => $field) {
            if(!in_array($key, self::FIELDS)) {
                $this->error = ['error' => true, 'barred_field' => str_replace("%field%", $key, self::ERRORS['barred_field'])];
                return false;
            } 
        }

        if(count($fields) !== count(self::FIELDS)) {
            $this->error = ['error' => true, 'required_fields' => str_replace("%fields%", implode(', ', self::FIELDS), self::ERRORS['required_fields'])];
            return false;
        }

        return true;
    }
}