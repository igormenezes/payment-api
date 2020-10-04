<?php

namespace Cronjob\Payment;

use Model\Entity\Transactions;
use Model\Payment\Billet;
use Model\Payment\Credit;
use Model\Payment\Debit;

class Process {
    public static function initialize() {
        $transactions = Transactions::select([
            'status' => 'failed' 
        ], 100);

        if(empty($transactions)) {
            die('Não há transações pendentes no momento!');
        }

        foreach($transactions as $transaction) {
            $payment = self::getPaymentType($transaction['type']);
            $payment->sendOrder($transaction, $transaction['id']);
        }

        die("transações processadas com sucesso!");
    }

    public static function getPaymentType($type) {
        switch($type) {
            case 'credit':
                return new Credit();
            break;
            case 'debit':
                return new Debit();
            break;
            case 'billet':
                return new Billet();
            break;
        }
    }
}