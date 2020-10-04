<?php

namespace Model\Payment;

use Model\Validation\Payment\Credit as Validation;

class Credit extends Payment {
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

        if(!$validation->validateInstallments($data['installments'])) {
            return $validation->error;
        }

        if(!$validation->validateCardData($data)) {
            return $validation->error;
        }

        if(!$validation->validateDateExpiration($data['date_expiration'])) {
            return $validation->error;
        }
    }
}