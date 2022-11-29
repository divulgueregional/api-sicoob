# ALTERAR MULTA BOLETO-SICOOB

## Alterar multa
Serviço para alterar valor multa de boletos informados.<br>
É possível a inclusão de até 10 boletos por requisição.

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
      "tipoMulta" => 0,
      "dataMulta" => "2023-01-02T00:00:00-03:00",
      "valorMulta" => 5
    ];
    $Boletos[] = $boleto;

    $reponse = $sicoob->multaBoleto($Boletos);
    print_r($reponse);
```