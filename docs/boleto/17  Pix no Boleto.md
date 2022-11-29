# PIX NO BOLETO-SICOOB

## Pix no boleto
Serviço para alterar boleto para utilização de PIX.<br>

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
      "utilizarPix" => true,// Indica se vai utilizar ou não o PIX.
    ];
    $Boletos[] = $boleto;

    $reponse = $sicoob->pixBoleto($Boletos);
    print_r($reponse);
```