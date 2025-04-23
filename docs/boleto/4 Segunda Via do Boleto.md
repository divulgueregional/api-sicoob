# SEGUNDA VIA BOLETO-SICOOB

## Consultar um boleto

Serviço para emissão da segunda via de boleto já registrado. Utiliza as informações do beneficiário logado (número da cooperativa, número identificador do beneficiário e conta corrente), juntamente com a informação do identificador do boleto (nosso número), ou da linha digitável ou do código de barras. Quando informados código de barras ou linha digitável, a pesquisa é realiazada prioritariamente por estes parâmetros.

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
        'numeroCliente' => (int) $dados->integracao_contrato,
        'codigoModalidade' => (int) 1,
        'nossoNumero' => $receber->boleto_nossoNumero,
        'linhaDigitavel' => $receber->boleto_linhaDigitavel,
        'codigoBarras' => $receber->boleto_codigoBarras,
        'gerarPdf' => true,
        'numeroContratoCobranca' => $receber->boleto_codigoCobranca,
    ];

    $reponse = $sicoob->segundaViaBoleto($params);
    print_r($reponse);
```
