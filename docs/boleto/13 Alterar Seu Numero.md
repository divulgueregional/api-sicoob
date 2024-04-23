# ALTERAR SEU NÚMERO BOLETO-SICOOB

## Alterar seu número
Serviço para alterar seu número e/ou número de controle da empresa dos boletos informados.<br>
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

    $alterarBoleto = [];//pode colcoar até 10 boltos em um array
    $boleto = [
      "numeroContrato" => 123456,//obrigatorio
      "modalidade" => 1,
      "nossoNumero" => 1234,
      "seuNumero" => "270.04",
      "identificacaoBoletoEmpresa" => "",
    ];
    $alterarBoleto[] = $boleto;

    $reponse = $sicoob->alterarSeuNumeroBoleto($alterarBoleto);
    print_r($reponse);
```