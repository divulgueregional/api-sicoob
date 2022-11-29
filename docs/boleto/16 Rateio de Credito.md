# RATEIO DE CRÉDITO BOLETO-SICOOB

## Rateio de Crédito
Serviço para comandar rateio de crédito de boletos informados.<br>
É possível a inclusão de até 10 boletos por requisição.<br>

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
      "rateioCreditos" => [
        "numeroBanco" => 756,
        "numeroAgencia" => 4027,
        "numeroContaCorrente" => 0,
        "contaPrincipal" => true,
        "codigoTipoValorRateio" => 1,
        "valorRateio" => 156.25,
        "codigoTipoCalculoRateio" => 1,
        "numeroCpfCnpjTitular" => 98765432185,
        "nomeTitular" => "Marcelo dos Santos",
        "codigoFinalidadeTed" => 10,
        "codigoTipoContaDestinoTed" => "CC",
        "quantidadeDiasFloat" => 1,
        "dataFloatCredito" => "2022-12-30",
      ]
    ];
    $Boletos[] = $boleto;

    $reponse = $sicoob->rateioCreditos($Boletos);
    print_r($reponse);
```