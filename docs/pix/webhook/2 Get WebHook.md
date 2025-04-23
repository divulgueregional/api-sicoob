# GET WEBHOOK PIX-SICOOB

## Get um webhook pix

consultar um webhook.<br>

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

    echo "<pre>";
    $response = $api->consultarWebhookPix($Post->chave_pix);
    print_r($reponse);

```
