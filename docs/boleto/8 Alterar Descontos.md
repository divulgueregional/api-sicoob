# ALTERAR DESCONTOS BOLETO-SICOOB

## Alterar descontos
Serviço para alterar informações de valor e/ou data de validade do desconto e/ou tipo de desconto de boletos informados. Os boletos do Sicoob permitem a inclusão de até 3 descontos do mesmo tipo. Neste serviço, é possível alterá-los separadamente (somente um, dois ou os três descontos simultaneamente).<br>
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
      "tipoDesconto" => 1,
      "dataPrimeiroDesconto" => "2023-01-02T00:00:00-03:00",
      "valorPrimeiroDesconto" => 1,
      "dataSegundoDesconto" => "2023-01-02T00:00:00-03:00",
      "valorSegundoDesconto" => 0,
      "dataTerceiroDesconto" => "2023-01-02T00:00:00-03:00",
      "valorTerceiroDesconto" => 0,
    ];
    $Boletos[] = $boleto;

    $reponse = $sicoob->descontosBoleto($Boletos);
    print_r($reponse);
```