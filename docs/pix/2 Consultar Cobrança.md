# CONSULTAR COBRANÇA PIX-SICOOB

## Consultar uma cobrança pix

consultar uma cobrança pix pelo txid do pix.<br>

```php
    require_once './../vendor/autoload.php';
    use Divulgueregional\ApiSicoob\BankingSicoobV3;

    $config = [
        'api' => 'pix', //boleto ou pix
        'client_id' => $Post->cliente_id,
        'codigoBeneficiario' => $Post->contrato,
        'certificate' => './api-sicoob/path/certificado.pem',//local do certificado crt
        'certificateKey' => './api-sicoob/path/chave.pem',//local do certificado key
        'token' => '',
        'sandbox' => false // true ativa sendbox, false = producao
    ];
    $sicoob = new BankingSicoob($config);

    $txid ='';

    echo "<pre>";
    $reponse = $sicoob->consultarCobrancaPix($txid);
    print_r($reponse);

```
