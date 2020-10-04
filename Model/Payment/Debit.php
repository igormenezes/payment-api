<?php

namespace Model\Payment;

use Model\Validation\Payment\Debit as Validation;

class Debit extends Payment {
    public function validation($data) {
        $validation = new Validation();

        if(!$validation->validateDefault($data)) {
            return $validation->error;
        }

        if(!$validation->validateFields($data)) {
            return $validation->error;
        }

        if(!$validation->validateFlag($data['flag'])) {
            return $validation->error;
        }

        if(!$validation->validateCardData($data)) {
            return $validation->error;
        }

        if(!$validation->validateDateExpiration($data['date_expiration'])) {
            return $validation->error;
        }

        if(!$validation->validateBankNumber($data['bank_number'])) {
            return $validation->error;
        }
    }
}