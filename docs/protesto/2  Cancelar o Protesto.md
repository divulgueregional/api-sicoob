# CANCELAR PROTESTO UM PAGADOR BOLETO-SICOOB

## Cancelar o protesto de um pagador de um boleto
Este serviço realiza a indicação de cancelamento de protesto de boletos informados. Os boletos em atraso e não pagos podem ser indicados a protesto. Caso seja realizado no mesmo dia, pode-se cancelar o apontamento a protesto.<br>
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

    $reponse = $sicoob->cancelarProtestoBoleto($Boletos);
    print_r($reponse);
 
```