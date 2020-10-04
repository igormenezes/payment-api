<?php

namespace Model\Validation\Payment;

abstract class Payment {
    const ERRORS = [
        'required_fields' => 'Verifique os campos enviados na transação. Na sua transação necessita dos seguintes campos: %fields%.',
        'invalid_full_name' => 'Por favor, informe o seu nome completo',
        'invalid_document' => 'Por favor, informe um cpf válido.',
        'invalid_amount' => 'O formato do valor a ser pago é invalido.',
        'barred_field' => 'O campo %field% foi barrado. São aceitos apenas os campos necessários para a transação.',
        'invalid_flag' => 'No momento só aceitamos as seguintes bandeiras: %flags%',
        'invalid_card_data' => "Dados do cartão inválido, verifique o número e senha informados.",
        'invalid_installments' => "Verifique a quantidade de parcelas informada. Valor máximo de parcelas é %installments%",
        'invalid_date_expiration' => "Data de expiração é inválida.",
        'invalid_bank_number' => "No momento só aceitamos os seguintes bancos: %banks%"
    ];

    public $error;

    public function validateDefault($data) {
        if(!$this->validateFullName($data['full_name'])) {
            return false;
        }
        
        if(!$this->validateDocument($data['document'])) {
            return false;
        }

        if(!$this->validateAmount($data['amount'])) {
            return false;
        }
       
        return true;
    }
    
    private function validateFullName($fullname) {
        if(empty($fullname) || !is_string($fullname)) {
            $this->error = ['error' => true, 'invalid_full_name' => self::ERRORS['invalid_full_name']];
            return false;
        }

        return true;
    }
    
    private function validateDocument($document) {
        if(!is_numeric($document)) {
            $this->error = ['error' => true, 'invalid_document' => self::ERRORS['invalid_document']];
            return false;
        }

        if (strlen($document) != 11) {
            $this->error = ['error' => true, 'invalid_document' => self::ERRORS['invalid_document']];
            return false;
        }

        if (preg_match('/(\d)\1{10}/', $document)) {
            $this->error = ['error' => true, 'invalid_document' => self::ERRORS['invalid_document']];
            return false;
        }

        return true;
    }

    private function validateAmount($amount) {
        if(!is_numeric($amount)) {
            $this->error = ['error' => true, 'invalid_amount' => self::ERRORS['invalid_amount']];
            return false;
        }

        return true;
    }
}