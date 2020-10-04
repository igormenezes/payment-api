<?php
namespace Test;

use PHPUnit\Framework\TestCase;

class Api extends TestCase {
    private $curl;
    private $opts = [];

	public function setUp() : void {
        $url = 'http://localhost/payment-api/index.php';
        $this->curl = curl_init();
        $this->opts[CURLOPT_URL] = $url;
        $this->opts[CURLOPT_RETURNTRANSFER] = true;
        $this->opts[CURLOPT_CUSTOMREQUEST] = 'POST';
        $this->opts[CURLOPT_CONNECTTIMEOUT] = 10;
        $this->opts[CURLOPT_TIMEOUT] = 10;
        $this->opts[CURLOPT_HTTPHEADER] = ['Content-Type: application/json'];
    }

    public function testValidationBarredFields() {
        $this->opts[CURLOPT_POSTFIELDS] = json_encode([	
            "full_name" => "João Silva",
            "document" => 98567414083,
            "amount" => 50.00,
            "type" => "billet",
            'another_field' => true
        ]);

        curl_setopt_array($this->curl, $this->opts);
        $responseBody = json_decode(curl_exec($this->curl), true);
        $responseCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

        $this->assertArrayHasKey('barred_field', $responseBody);
        $this->assertEquals(400, $responseCode);
    }
    
    public function testValidationFullName() {
        $this->opts[CURLOPT_POSTFIELDS] = json_encode([
            "document" => 98567414083,
            "amount" => 50.00,
            "type" => "billet"
        ], JSON_UNESCAPED_UNICODE);
        curl_setopt_array($this->curl, $this->opts);
        $responseBody = json_decode(curl_exec($this->curl), true);
        $responseCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

        $this->assertArrayHasKey('invalid_full_name', $responseBody);
        $this->assertEquals(400, $responseCode);
    }

    public function testValidationDocument() {
        $this->opts[CURLOPT_POSTFIELDS] = json_encode([
            "full_name" => "João Silva",
            "document" => 11111111111,
            "amount" => 50.00,
            "type" => "billet"
        ], JSON_UNESCAPED_UNICODE);
        curl_setopt_array($this->curl, $this->opts);
        $responseBody = json_decode(curl_exec($this->curl), true);
        $responseCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

        $this->assertArrayHasKey('invalid_document', $responseBody);
        $this->assertEquals(400, $responseCode);
    }

    public function testValidationAmount() {
        $this->opts[CURLOPT_POSTFIELDS] = json_encode([	
            "full_name" => "João Silva",
            "document" => 98567414083,
            "amount" => "50,00",
            "type" => "billet"
        ], JSON_UNESCAPED_UNICODE);
        curl_setopt_array($this->curl, $this->opts);
        $responseBody = json_decode(curl_exec($this->curl), true);
        $responseCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

        $this->assertArrayHasKey('invalid_amount', $responseBody);
        $this->assertEquals(400, $responseCode);
    }

    public function testSuccess() {
        $this->opts[CURLOPT_POSTFIELDS] = json_encode([	
            "full_name" => "João Silva",
            "document" => 98567414083,
            "amount" => 50.00,
            "type" => "billet"
        ], JSON_UNESCAPED_UNICODE);
        curl_setopt_array($this->curl, $this->opts);
        $responseBody = json_decode(curl_exec($this->curl), true);
        $responseCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

        $this->assertArrayHasKey('id_transaction', $responseBody);
        $this->assertEquals(200, $responseCode);
    }

	public function tearDown() : void {
        curl_close($this->curl);
	}
}