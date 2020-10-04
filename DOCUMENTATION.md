## Endpoints API
* `/index.php`: API server, onde recebemos os pagamentos
* `/cronjob.php`: Cron para ser executado de tempos em tempos para processar os pedidos com falha.
* Exemplos request de pagamento:
    <pre>
    Crédito
	{	
		"full_name": "João Silva",
		"document": 98567414083,
		"amount": 50.00,
		"installments": 2,
		"card_number": 4242424242424242,
		"date_expiration": "10/25",
		"password": 123, 
		"type": "credit", 
		"flag": "mastercard",
	} 
    </pre>
    <pre>
    Débito
	{	
		"full_name": "João Silva",
		"document": 98567414083,
		"amount": 50.00,
		"bank_number": 341,
		"card_number": 4242424242424242,
		"date_expiration": "10/25",
		"type": "debit",
		"flag": "mastercard"
	}  
    </pre>
    <pre>
    Boleto
	{
		"full_name": "João Silva",
		"document": 98567414083,
		"amount": 50.00,
		"type": "billet"
	}
    </pre>

## Modelagem Banco:

* transactions
	* `id` (chave primaria)
	* `id_transaction` (id gerado pelo gateway de pagamento)
	* `type` (credito, debito, boleto)
	* `flag` (mastercard, visa)
	* `last_card_digits` (apenas salvar os últimos 4 digitos do cartão, nas compras com cartão),
	* `status` (processing, pending, approved, failed) 
	* `full_name` (nome completo)
	* `document` (cpf)
	* `amount` (valor total da transação)
	* `installment` (quantidade de parcelas no caso de pagamento em credito)
	* `date` (data da transação)