<?php

namespace Model\Validation\Payment;

class Credit extends Payment {
    const FIELDS = [
        'full_name',
        'document',
        'amount',
        'installments',
        'card_number',
        'date_expiration',
        'password',
        'type',
        'flag'
    ];

    const FLAGS = [
        'mastercard',
        'visa'
    ];

    const FLAGS_RULES = [
        'mastercard' => [
            'quantity_digits_card' => 16,
            'quantity_digits_password' => 3
        ],
        'visa' => [
            'quantity_digits_card' => 16,
            'quantity_digits_password' => 3
        ]
    ];

    const MAX_INSTALLMENTS = 12;

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

    public function validateFlag($flag) {
        if(!in_array(strtolower($flag), self::FLAGS)) {
            $this->error = ['error' => true, 'invalid_flag' => str_replace("%fields%", implode(', ', self::FLAGS), self::ERRORS['invalid_flag'])];
            return false;
        }

        return true;
    }

    public function validateInstallments($installments) {
        if(!is_numeric($installments) || $installments > self::MAX_INSTALLMENTS) {
            $this->error = ['error' => true, 'invalid_installments' => str_replace("%installments%", self::MAX_INSTALLMENTS, self::ERRORS['invalid_installments'])];
            return false;
        }

        return true;
    }

    public function validateCardData($data) {
        $validationCardNumber = is_numeric($data['card_number']) && strlen($data['card_number']) === self::FLAGS_RULES[strtolower($data['flag'])]['quantity_digits_card'];
        $validationPassword = is_numeric($data['password']) && strlen($data['password']) === self::FLAGS_RULES[strtolower($data['flag'])]['quantity_digits_password'];

        if(!$validationCardNumber || !$validationPassword) {
            $this->error = ['error' => true, 'invalid_card_data' => self::ERRORS['invalid_card_data']];
            return false;
        }

        return true;
    }

    public function validateDateExpiration($date) {
        $month = date('m');
        $year = date('y');

        $date = explode('/', $date);

        if($date[1] < $year || ($date[0] < $month && $date[1] == $year)) {
            $this->error = ['error' => true, 'invalid_date_expiration' => self::ERRORS['invalid_date_expiration']];
            return false;
        }
        
        return true;
    }
}