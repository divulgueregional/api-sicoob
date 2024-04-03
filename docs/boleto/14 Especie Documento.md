# ALTERAR ESPÉCIE DOCUMENTO BOLETO-SICOOB

## Alterar espécie documento
Serviço para alterar a informação da espécie de documento dos boletos informados.<br>
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
      "especieDocumento" => "DM",
    ];
    $Boletos[] = $boleto;

    $reponse = $sicoob->especieDocumento($Boletos);
    print_r($reponse);
```