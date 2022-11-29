# BAIXAR NEGATIVAÇÃO DE UM PAGADOR BOLETO-SICOOB

## Baixar a negativação de um pagador de um boleto
Serviço para comandar uma baixa da negativação de pagadores de boletos informados. Será enviado um pedido de baixa para o serviço de proteção ao crédito.<br>
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

    $reponse = $sicoob->baixarNegativarBoleto($Boletos);
    print_r($reponse);
 
```