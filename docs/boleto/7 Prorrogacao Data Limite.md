# PRORROGAR DATA LIMITE BOLETO-SICOOB

## Prorrogar a data de limite
Serviço para prorrogação da data limite de pagamento de boletos informados.<br>
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

    $postegarBoleto = [];//pode colcoar até 10 boltos em um array
    $boleto = [
      "numeroContrato" => 123456,//obrigatorio
      "modalidade" => 1,
      "nossoNumero" => 1234,
      "dataLimitePagamento" => "2023-01-02T00:00:00-03:00"
    ];
    $postegarBoleto[] = $boleto;

    $reponse = $sicoob->prorrogarDataLimite($postegarBoleto);
    print_r($reponse);
```