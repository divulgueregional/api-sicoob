# CRIAR COBRANÇA PIX-SICOOB

## Criar uma cobrança pix

gerar uma cobrança pix por txid.<br>

```php
    require_once './../vendor/autoload.php';
    use Divulgueregional\ApiSicoob\BankingSicoobV3;

    $config = [
        'api' => 'pix', //boleto ou pix
        'client_id' => $Post->cliente_id,
        'codigoBeneficiario' => $Post->contrato,
        'certificate' => './api-sicoob/path/certificado.pem',//local do certificado crt
        'certificateKey' => './api-sicoob/path/chave.pem',//local do certificado key
        'token' => '',
        'sandbox' => false // true ativa sendbox, false = producao
    ];
    $sicoob = new BankingSicoob($config);

    function gerarUUIDv4(): string
    {
        $data = random_bytes(16);

        // Define a versão para 0100 (versão 4)
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Define os bits de variante para 10 (RFC 4122)
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    $uuid = gerarUUIDv4();
    $txid = str_replace('-', '', $uuid);

    $fields = [
    'calendario' => [
        "expiracao" => 3600
    ],
    // 'devedor' => [
    //     "cpf" => 'cpf_pagador',
    //     "nome" => 'nome_pagador'
    // ],
    // 'loc' => [
    //     "id" => 0
    // ],
    'valor' => [
        "original" => '1.00'
    ],
    'chave' => 'sua_chave_pix',
    'solicitacaoPagador' => 'teste pix',
    // 'infoAdicionais' => [
    //     "nome" => 'Referência',
    //     "valor" => 'Pedido 123'
    // ],
];

echo "<pre>";
$reponse = $sicoob->criarCobrancaPix($fields, $txid);
print_r($reponse);

```
