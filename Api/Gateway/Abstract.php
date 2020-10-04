<?php 

namespace Api\Gateway;

abstract class Gateway {
    protected $curl;
    protected $opts;

    public function __construct() {
        $this->curl = curl_init();
        $this->opts[CURLOPT_RETURNTRANSFER] = true;
        $this->opts[CURLOPT_CONNECTTIMEOUT] = 10;
        $this->opts[CURLOPT_TIMEOUT] = 10;
    }

    public function send($url, $method, $data = []) {
        $this->opts[CURLOPT_URL] = $url;
        $this->opts[CURLOPT_CUSTOMREQUEST] = $method;

        if($method == 'POST') {
            $this->opts[CURLOPT_POSTFIELDS] = $data;
        }

        curl_setopt_array($this->curl, $this->opts);
        $responseBody = json_decode(curl_exec($this->curl), true);
        $responseCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        curl_close($this->curl);
        return $this->response($responseBody, $responseCode);
    }

    protected function response($response, $status) {
        return ['body' => $response, 'status' => $status];
    }
}