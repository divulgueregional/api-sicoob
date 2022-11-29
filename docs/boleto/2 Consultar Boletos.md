# CONSULTAR BOLETO-SICOOB

## Consultar um boleto
Serviço para consulta de um boleto bancário. Utiliza as informações do beneficiário logado (número da cooperativa, número identificador do beneficiário e conta corrente), juntamente com a informação do identificador do boleto (nosso número), ou da linha digitável ou do código de barras.

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
        "numeroContrato" => 123456,
        'modalidade' => 1,
        'nossoNumero' => '4552',
        'linhaDigitavel' => '',
        'codigoBarras' => '',
    ];
    $reponse = $sicoob->consultarBoleto($filters);
    print_r($reponse);  
```