# SEGUNDA VIA BOLETO-SICOOB

## Consultar um boleto
Serviço para emissão da segunda via de boleto já registrado. Utiliza as informações do beneficiário logado (número da cooperativa, número identificador do beneficiário e conta corrente), juntamente com a informação do identificador do boleto (nosso número), ou da linha digitável ou do código de barras. Quando informados código de barras ou linha digitável, a pesquisa é realiazada prioritariamente por estes parâmetros.

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
        "modalidade" => 1,// Identifica a modalidade do boleto. 1 SIMPLES COM REGISTRO; 5 CARNÊ DE PAGAMENTOS; 6 INDEXADA; 14 CARTÃO DE CRÉDITO
        "nossoNumero" => 1234,// Número identificador do boleto no Sisbr.Caso seja informado, não é necessário informar a linha digitável ou código de barras.
        "linhaDigitavel" => '', // Número da linha digitável do boleto com 47 posições.Caso seja informado, não é necessário informar o nosso número ou código de barras.
        "codigoBarras" => '', // Número de código de barras do boleto com 44 posições.Caso seja informado, não é necessário informar o nosso número ou a linha digitável.
        "gerarPdf" => 'true', //Identificador para o sistema devolver ou não o PDF do Boleto. O PDF será retornado na Base64.
    ];

    $sicoob->setToken($token);
    $reponse = $sicoob->segundaViaBoleto($filters);
    print_r($reponse); 
```