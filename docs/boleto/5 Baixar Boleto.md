# BAIXAR BOLETO-SICOOB

## Baixar boleto

Serviço para comandar a baixa de boletos informados.<br>
É possível a inclusão de até 10 boletos por requisição.<br>

```php
    require_once './../vendor/autoload.php';
    use Divulgueregional\ApiSicoob\BankingSicoobV3;

    $config = [
        'api' => 'boleto', //boleto ou pix
        'client_id' => $Post->cliente_id,
        'codigoBeneficiario' => $Post->contrato,
        'certificate' => './api-sicoob/path/certificado.pem',//local do certificado crt
        'certificateKey' => './api-sicoob/path/chave.pem',//local do certificado key
        'token' => '',
        'sandbox' => false // true ativa sendbox, false = producao
    ];
    $sicoob = new BankingSicoob($config);

    $params = [
      'numeroCliente' => (int) $Post->numeroCliente,
      'codigoModalidade' => (int) 1,
      'nossoNumero' => $Post->nossoNumero,
    ];

    $reponse = $sicoob->baixaBoleto($Boletos);
    print_r($reponse);
```
