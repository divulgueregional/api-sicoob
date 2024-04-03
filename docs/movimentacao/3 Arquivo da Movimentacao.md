# DOWNLOAD DO ARQUIVO DA MOVIMENTAÇÃO BOLETO-SICOOB

## Download do arquivo da movimentação
Serviço para obter um arquivo de movimentação.

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
      "codigoSolicitacao" => 3576899,
      "idArquivo" => 5004,
    ];

    $reponse = $sicoob->downloadMovimentacao($filters);
    print_r($reponse);
 
```