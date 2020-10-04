<?php

foreach(glob('Database' . '/*.php') as $filename) {
    require $filename;
}

foreach(glob('Api/Gateway' . '/*.php') as $filename) {
    require $filename;
}

foreach(glob('Model/Entity' . '/*.php') as $filename) {
    require $filename;
}

foreach(glob('Model/Payment' . '/*.php') as $filename) {
    require $filename;
}

foreach(glob('Model/Validation/Payment' . '/*.php') as $filename) {
    require $filename;
}

use Model\Payment\Credit;
use Model\Payment\Debit;
use Model\Payment\Billet;

try {
    
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if(empty($data['type'])) {
        throw new \Exception();    
    }

    switch($data['type']) {
        case 'credit':
            $payment = new Credit();
        break;
        case 'debit':
            $payment = new Debit();
        break;
        case 'billet':
            $payment = new Billet();
        break;
        default:
            throw new \Exception(); 
        break;
    }

    $return = $payment->validation($data);

    if(empty($return['error'])) {
        $return = $payment->process($data);
    }

    if(empty($return['success'])) {
        http_response_code(400);
    }

    die(json_encode($return, JSON_UNESCAPED_UNICODE));
} catch(\PDOException $e) {
    die(json_encode('Ocorreu ao processar as informações no banco de dados. Se persistir, entre em contato.', JSON_UNESCAPED_UNICODE));
} catch(\Exception $e) {
    die(json_encode("Ocorreu um erro, por favor tente novamente ou entre em contato.", JSON_UNESCAPED_UNICODE));
}