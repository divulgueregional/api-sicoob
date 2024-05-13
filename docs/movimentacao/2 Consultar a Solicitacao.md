# CONSULTAR A SOLICITAÇÃO DA MOVIMENTAÇÃO BOLETO-SICOOB

## Consultar a solicitação da movimentação
Serviço para consultar a situação da solicitação da movimentação.

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

    $filters = [
      "numeroContrato" => 123456,//obrigatorio
      "codigoSolicitacao" => 654321,
    ];
    
    $reponse = $sicoob->consultarMovimentacao($filters);
    print_r($reponse);
 
```