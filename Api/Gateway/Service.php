<?php

namespace Api\Gateway;

class Service extends Gateway {
    //Simulation api gateway service, example(Cielo, Moip, etc)..
    public function send($url, $method, $data = []) {
        return $this->response([
            'status' => $data['type'] == 'billet' ? 'pending' : 'approved', 
            'id_transaction' => md5(time())
        ], 200);
    }

    protected function response($response, $status) {
        if($status >= 400) {
            return [
                'error' => true,
                'message' => 'Erro ao processar pagamento com o gateway. Fique tranquilo que o seu pagamento será processado em breve!',
                'status' => 'failed'
            ];
        }

        return [
            'success' => true,
            'status' => $response['status'],
            'id_transaction' => $response['id_transaction']
        ];
    }
}