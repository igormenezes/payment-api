## Projeto Versão 1.0
* Proposito: API para realização de pagamentos online.
* Principais tecnologias: Foi utilizado PHP, Apache e SQLite.
* Camadas: 
    * Api:
        * Gateway: simulando um API REST Client para processar as transações no gateway. (Simulação de uma resposta do gateway).
    * Database: Conexão e banco de dados existentes.
    * Model: 
        * Entity: Toda a estrutura de banco de dados.
        * Payment: Processo de salvar pedido, processar no gateway e atualizar as informalções de pagamento.
        * Validation: Validação dos dados de pagamento.
    * Cronjob: Cron que rode de tempos em tempos, para processar os pedidos que tiveram falha e realizar a tentativa novamnete.
    * Test: Realização dos testes unitários. (Pagamentos com crédito, débito e boleto).
* Observações:
    * Pagamentos com cartão de crédito e débito são simulados como aprovado, no caso de boleto o retorno é pendente, pois aguarda o usuário realizar o pagamento.
    * Versão 2.0: Ídeia futura de melhoria é ter uma `versão 2.0`, para obter as respostas do gateway, quando o boleto foi pago, estorno (valor total ou parcial), etc.

## Tutorial instalação das ferramentas
1. Instalação das ferramentas via terminal (PHP, servidor Apache, PHPUnit, composer)
    * `sudo apt install apache2`
    * `sudo apt install php7.4`
    * `sudo apt install libapache2-mod-php`
    * `sudo apt install sqlite`
    * `sudo apt-add-repository ppa:ondrej/php`
    * `sudo apt-get install php7.4-sqlite3`
    * `sudo apt-get install php7.4-curl`
    * `sudo apt install phpunit`
    * `sudo apt install composer`
1. Configurar : 
    * `/etc/apache2/sites-available/000-default.conf`
        <pre>
        VirtualHost *:80
        ServerAdmin localhost
        DocumentRoot /var/www/
        VirtualHost
        </pre>
    * `/etc/hosts`
        <blockquote>
        127.0.0.1 localhost
        </blockquote>
    * `/etc/php/7.4/apache2/php.ini`
        <pre>
        display_errors = On
        extension=pdo_sqlite
        extension=sqlite3
        extension=curl
        </pre>
1. Rodar no terminal: `sudo a2enmod rewrite` e depois `sudo service apache2 restart`
1. Instalar as depedências do projeto: `composer install`
1. Rodar os testes de unidade: 
    * `vendor/bin/phpunit Test/Credit.php`
    * `vendor/bin/phpunit Test/Debit.php`
    * `vendor/bin/phpunit Test/Billet.php`
1. Explicação sobre os `endpoints`, se encontra em DOCUMENTATION.md
1. `IMPORTANTE`: Caso estiver com problemas para inserir registros no banco de dados, alterar permissões de usuário: 
    * `chown -R your_user:www-data api-payment/`