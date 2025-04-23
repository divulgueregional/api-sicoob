# CONSULTAR BOLETO-SICOOB

## Consultar um boleto

Serviço para consulta de um boleto bancário. Utiliza as informações do beneficiário logado (número da cooperativa, número identificador do beneficiário e conta corrente), juntamente com a informação do identificador do boleto (nosso número), ou da linha digitável ou do código de barras.

```php
    require_once './../vendor/autoload.php';
    use Divulgueregional\ApiSicoob\BankingSicoobV3;

    $config = [
        'api' => 'boleto', //boleto ou pix
        'client_id' => $Post->cliente_id,
        'codigoBeneficiario' => $Post->contrato,
        'certificate' => './api-sicoob/path/certificado.pem',//local do certificado crt
        'certificateKey' => './api-sicoob/path/chave.pem',//local do certificado key
        'token' => '',
        'sandbox' => false // true ativa sendbox, false = producao
    ];
    $sicoob = new BankingSicoob($config);

    $params = [
        'numeroCliente' => (int) $Post->contrato,
        'codigoModalidade' => (int) 1,
        'linhaDigitavel' => $boleto_linhaDigitavel,
        'codigoBarras' => $boleto_codigoBarras,
        'numeroContratoCobranca' => $boleto_codigoCobranca,
    ];
    $reponse = $sicoob->consultarBoleto($params);
    print_r($reponse);
```
