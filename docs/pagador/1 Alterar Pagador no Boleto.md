# ALTERAR PAGADOR BOLETO-SICOOB

## Alterar pagador de um boleto
Serviço para alterar informações de pagadores vinculado aos boletos informados.<br>
* É possível a inclusão de até 10 (dez) boletos por requisição.

```php
    require_once './api-sicoob/vendor/autoload.php';
    use Divulgueregional\ApiSicoob\BankingSicoob;

    $config = [
    'api' => 'boleto', //boleto ou pix
    'client_id' => '',
    'certificate' => './api-sicoob/path/certificado.pem',//local do certificado crt
    'certificateKey' => './api-sicoob/path/chave.pem',//local do certificado key
    'token' => $token, // se não info
    ];
    $sicoob = new BankingSicoob($config);

    $Boletos = [];//pode colcoar até 10 boltos em um array
    $boleto = [
      "numeroContrato" => 123456,//obrigatorio
      "modalidade" => 1,
      "nossoNumero" => 1234,
      "numeroCpfCnpj" => '98765432185',
      "nome" => 'Marcelo dos Santos',
      "endereco" => 'Rua 87 Quadra 1 Lote 1 casa 1',
      "bairro" => 'Santa Rosa',
      "cidade" => 'Luziânia',
      "cep" => '72320000',
      "uf" => 'DF',
      "email" => [
        "",// Lista de e-mais do Pagador. string
      ]
    ];
    $Boletos[] = $boleto;

    $reponse = $sicoob->alterarPagadores($Boletos);
    print_r($reponse);
 
```