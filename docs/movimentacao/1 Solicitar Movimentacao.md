# SOLICITAR MOVIMENTAÇÃO BOLETO-SICOOB

## Solicitar movimentações dos boletos
Serviço para solicitar a movimentação da carteira de cobrança registrada para beneficiário informado. Os movimentos disponíveis para solicitaçao são 1. Entrada 2. Prorrogação 3. A Vencer 4. Vencido 5. Liquidação 6. Baixa<br>
* As consultas estão limitadas em um período máximo de 2 dias.

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

    $filters = [
      "numeroContrato" => 123456,//obrigatorio
      "tipoMovimento" => 1,//1. Entrada 2. Prorrogação 3. A Vencer 4. Vencido 5. Liquidação 6. Baixa
      "dataInicial" => "2022-12-26T00:00:00-03:00",// As consultas estão limitadas em um período máximo de 2 dias.
      "dataFinal" => "2022-12-28T00:00:00-03:00",
    ];
    
    $reponse = $sicoob->solicitarMovimentacao($filters);
    print_r($reponse);
 
```