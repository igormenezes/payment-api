<?php

namespace Model\Payment;

use Model\Validation\Payment\Billet as Validation;

class Billet extends Payment {
    public function validation($data) {
        $validation = new Validation();

        if(!$validation->validateDefault($data)) {
            return $validation->error;
        }

        if(!$validation->validateFields($data)) {
            return $validation->error;
        }
    }
}