# ALTERAR VALOR NOMINAL BOLETO-SICOOB

## Alterar valor nominal
Serviço para alterar o valor nominal de boletos informados.<br>
É possível a inclusão de até 10 boletos por requisição.<br>
<b>Só será permitido alterar valor nominal de boletos da modalidade cartão de crédito.</b>

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
      "valor" => 20.55,
    ];
    $Boletos[] = $boleto;

    $reponse = $sicoob->valorNominalBoleto($Boletos);
    print_r($reponse);
```