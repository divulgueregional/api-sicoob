# NEGATIVAR UM PAGADOR BOLETO-SICOOB

## Negativar um pagador de um boleto
Serviço para indicar a negativação de pagadores de boletos informados. A negativação é o registro de pendências ou restrições nos órgãos de proteção ao crédito. No Sicoob, o parceiro para este serviço é o SERASA.<br>
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

    $reponse = $sicoob->negativarBoleto($Boletos);
    print_r($reponse);
 
```