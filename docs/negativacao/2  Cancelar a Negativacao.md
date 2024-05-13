# CANCELAR NEGATIVAR UM PAGADOR BOLETO-SICOOB

## Cancelar a negativação de um pagador de um boleto
Serviço para cancelar o apontamento da negativação de pagadores de boletos informados. O boleto não será enviado ao serviço de proteção ao crédito.<br>
* É possível a inclusão de até 10 boletos por requisição.

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
    ];
    $Boletos[] = $boleto;
 
    $reponse = $sicoob->cancelarNegativarBoleto($Boletos);
    print_r($reponse);
 
```