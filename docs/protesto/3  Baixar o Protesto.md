# BAIXAR PROTESTO DE UM PAGADOR BOLETO-SICOOB

## Baixar o protesto de um pagador de um boleto
Desistir do protesto do boleto<br>
Este serviço realiza o pedido de desistência do protesto de boletos informados. O pedido de desistência não garante que o protesto será retirado. Deve-se aguardar o retorno do cartório. O pedido de desistência pode ser realizado a qualquer momento, desde que haja um apontamento prévio.<br>
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

    $reponse = $sicoob->desistirProtestoBoleto($Boletos);
    print_r($reponse);
 
```