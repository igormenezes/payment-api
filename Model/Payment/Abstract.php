<?php

namespace Model\Payment;

use Api\Gateway\Service;
use Model\Entity\Transactions;

abstract class Payment {
    public function process($data) {
        $id = $this->saveOrder($data);
        return $this->sendOrder($data, $id);
    }

    private function saveOrder($data) {
        $data['status'] = 'processing';
        return Transactions::insert([
            'full_name' => $data['full_name'],
            'document' => $data['document'],
            'amount' => $data['amount'],
            'type' => $data['type'],
            'status' => 'processing',
            'flag' => isset($data['flag']) ? $data['flag'] : null,
            'last_card_digits' => isset($data['card_number']) ? substr($data['card_number'], -4) : null,
            'installments' => isset($data['installments']) ? $data['installments'] : null,
            'date' => date('Y-m-d H:i:s')
        ]);
    }

    public function sendOrder($data, $id) {
        $service = new Service();
        $response = $service->send('http:://simulation-gateway.com', 'POST', $data);
        Transactions::update([
            'id_transaction' => isset($response['id_transaction']) ? $response['id_transaction'] : null,
            'status' => $response['status']
        ], $id);

        return $response;
    }
}