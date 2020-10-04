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
            "bank_number" => 341,
            "card_number" => 4242424242424242,
            "date_expiration" => "10/25",
            "type" => "debit",
            "flag" => "mastercard",
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
            "bank_number" => 341,
            "card_number" => 4242424242424242,
            "date_expiration" => "10/25",
            "type" => "debit",
            "flag" => "mastercard"
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
            "bank_number" => 341,
            "card_number" => 4242424242424242,
            "date_expiration" => "10/25",
            "type" => "debit",
            "flag" => "mastercard"
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
            "amount" => '50,00',
            "bank_number" => 341,
            "card_number" => 4242424242424242,
            "date_expiration" => "10/25",
            "type" => "debit",
            "flag" => "mastercard",
        ], JSON_UNESCAPED_UNICODE);
        curl_setopt_array($this->curl, $this->opts);
        $responseBody = json_decode(curl_exec($this->curl), true);
        $responseCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

        $this->assertArrayHasKey('invalid_amount', $responseBody);
        $this->assertEquals(400, $responseCode);
    }
	
	public function testValidationFields() {
        $this->opts[CURLOPT_POSTFIELDS] = json_encode([	
            "full_name" => "João Silva",
            "document" => 98567414083,
            "amount" => 50.00,
            "type" => "debit",
            "flag" => "mastercard"
        ], JSON_UNESCAPED_UNICODE);
        curl_setopt_array($this->curl, $this->opts);
        $responseBody = json_decode(curl_exec($this->curl), true);
        $responseCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

        $this->assertArrayHasKey('required_fields', $responseBody);
        $this->assertEquals(400, $responseCode);
    }

    public function testValidationFlag() {
        $this->opts[CURLOPT_POSTFIELDS] = json_encode([	
            "full_name" => "João Silva",
            "document" => 98567414083,
            "amount" => 50.00,
            "bank_number" => 341,
            "card_number" => 4242424242424242,
            "date_expiration" => "10/25",
            "type" => "debit",
            "flag" => "american express"
        ], JSON_UNESCAPED_UNICODE);
        curl_setopt_array($this->curl, $this->opts);
        $responseBody = json_decode(curl_exec($this->curl), true);
        $responseCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

        $this->assertArrayHasKey('invalid_flag', $responseBody);
        $this->assertEquals(400, $responseCode);
    }

    public function testValidationCardNumber() {
        $this->opts[CURLOPT_POSTFIELDS] = json_encode([	
            "full_name" => "João Silva",
            "document" => 98567414083,
            "amount" => 50.00,
            "bank_number" => 341,
            "card_number" => 424242424242424,
            "date_expiration" => "10/25",
            "type" => "debit",
            "flag" => "mastercard"
        ], JSON_UNESCAPED_UNICODE);
        curl_setopt_array($this->curl, $this->opts);
        $responseBody = json_decode(curl_exec($this->curl), true);
        $responseCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

        $this->assertArrayHasKey('invalid_card_data', $responseBody);
        $this->assertEquals(400, $responseCode);
    }

    public function testValidationDateExpiration() {
        $this->opts[CURLOPT_POSTFIELDS] = json_encode([	
            "full_name" => "João Silva",
            "document" => 98567414083,
            "amount" => 50.00,
            "bank_number" => 341,
            "card_number" => 4242424242424242,
            "date_expiration" => "12/19",
            "type" => "debit",
            "flag" => "mastercard"
        ], JSON_UNESCAPED_UNICODE);
        curl_setopt_array($this->curl, $this->opts);
        $responseBody = json_decode(curl_exec($this->curl), true);
        $responseCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

        $this->assertArrayHasKey('invalid_date_expiration', $responseBody);
        $this->assertEquals(400, $responseCode);
    }

    public function testValidationBankNumber() {
        $this->opts[CURLOPT_POSTFIELDS] = json_encode([	
            "full_name" => "João Silva",
            "document" => 98567414083,
            "amount" => 50.00,
            "bank_number" => 001,
            "card_number" => 4242424242424242,
            "date_expiration" => "10/25",
            "type" => "debit",
            "flag" => "mastercard"
        ], JSON_UNESCAPED_UNICODE);
        curl_setopt_array($this->curl, $this->opts);
        $responseBody = json_decode(curl_exec($this->curl), true);
        $responseCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

        $this->assertArrayHasKey('invalid_bank_number', $responseBody);
        $this->assertEquals(400, $responseCode);
    }

    public function testSuccess() {
        $this->opts[CURLOPT_POSTFIELDS] = json_encode([	
            "full_name" => "João Silva",
            "document" => 98567414083,
            "amount" => 50.00,
            "bank_number" => 341,
            "card_number" => 4242424242424242,
            "date_expiration" => "10/25",
            "type" => "debit",
            "flag" => "mastercard"
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