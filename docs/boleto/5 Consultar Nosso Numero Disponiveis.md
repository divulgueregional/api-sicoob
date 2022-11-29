# CONSULTA NOSSO NÚMERO BOLETO-SICOOB

## Consultar de dados de faixas de nosso número disponíveis
Serviço para consulta de dados de faixas de nosso número disponíveis.

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
        "modalidade" => 1,// Identifica a modalidade do boleto.; 1 SIMPLES COM REGISTRO;3 CAUCIONADA;4 VINCULADA;8 COBRANÇA CONTA CAPITAL
        "quantidade" => 10,// Quantidade mínima de nosso números que devem estar disponíveis na faixa a ser pesquisada.
    ];
    $reponse = $sicoob->faixasNossoNumeroDisponivel($filters);
    print_r($reponse);
```