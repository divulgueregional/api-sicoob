# REGISTRAR BOLETO-SICOOB

## Gerar ou criar um boleto
Serviço para a inclusão de boletos informados.<br>
* É possível a inclusão de até 10 (dez) boletos por requisição.

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

    // OPÇOES DA ESPECIE DO DOCUMENTO
    // CH - Cheque
    // DM - Duplicata Mercantil
    // DMI - Duplicata Mercantil Indicação
    // DS - Duplicata de Serviço
    // DSI - Duplicata Serviço Indicação
    // DR - Duplicata Rural
    // LC - Letra de Câmbio
    // NCC - Nota de Crédito Comercial
    // NCE - Nota de Crédito Exportação
    // NCI - Nota de Crédito Industrial
    // NCR - Nota de Crédito Rural
    // NP - Nota Promissória
    // NPR - Nota Promissória Rural
    // TM - Triplicata Mercantil
    // TS - Triplicata de Serviço
    // NS - Nota de Seguro
    // RC - Recibo
    // FAT - Fatura
    // ND - Nota de Débito
    // AP - Apólice de Seguro
    // ME - Mensalidade Escolar
    // PC - Pagamento de Consórcio
    // NF - Nota Fiscal
    // DD - Documento de Dívida
    // CC - Cartão de Crédito
    // BDP - Boleto Proposta
    // OU - Outros

    $arrayBoletos = [];//pode colcoar até 10 boltos em um array
    $incluirBoleto = [
    "numeroContrato" => 123456,//Número que identifica o contrato do beneficiário no Sisbr. Número da abertura da conta
    "modalidade" => 1,//1 - SIMPLES COM REGISTRO
    "numeroContaCorrente" => 123456,// Número da Conta Corrente onde será realizado o crédito da liquidação do boleto.
    "especieDocumento" => "DM",// OPÇOES DA ESPECIE DO DOCUMENTO
    "dataEmissao" => "{$data}T{$hora}-03:00",// Data de emissão do boleto. Caso não seja informado, o sistema atribui a data de registro do boleto no Sisbr.
    "nossoNumero" => '',// deixa em branco que o banco preenche
    "seuNumero" => 270.05,// Número identificador do boleto no sistema do beneficiário. Tamanho máximo 18
    "identificacaoBoletoEmpresa" => 270,//Campo destinado para uso da empresa do beneficiário para identificação do boleto. String Tamanho máximo 25
    "identificacaoEmissaoBoleto" => 2,// Código de identificação de emissão do boleto. Informar os valores listados abaixo. - 1 Banco Emite - 2 Cliente Emite
    "identificacaoDistribuicaoBoleto" => 2,// Código de identificação de distribuição do boleto. Informar os valores listados abaixo. - 1 Banco Distribui - 2 Cliente Distribui
    "valor" => 10.00,// Valor nominal do boleto. number($double)
    "dataVencimento" => "{$vencimento}T00:00:00-03:00",// Data de vencimento do boleto. string($date)
    "dataLimitePagamento" => "{$dataLimite}T00:00:00-03:00",// Data de limite para pagamento do boleto. string($date)
    "valorAbatimento" => '',// Valor do abatimento a ser aplicado no boleto. number($double)
    "tipoDesconto" => 0,// Informar o tipo de desconto atribuido ao boleto.
    // - 0 Sem Desconto
    // - 1 Valor Fixo Até a Data Informada
    // - 2 Percentual até a data informada
    // - 3 Valor por antecipação dia corrido
    // - 4 Valor por antecipação dia útil
    // - 5 Percentual por antecipação dia corrido
    // - 6 Percentual por antecipação dia útil
    "dataPrimeiroDesconto" => '', // Data do primeiro desconto. string($date) example: 2018-09-20T00:00:00-03:00
    "valorPrimeiroDesconto" => '', // Valor do primeiro desconto. Deve ser informado caso a data do primeiro desconto seja preenchida. 	number($double)
    "dataSegundoDesconto" => '', // Data do segundo desconto. string($date) example: 2018-09-20T00:00:00-03:00
    "valorSegundoDesconto" => '', // Valor do segundo desconto. Deve ser informado caso a data do segundo desconto seja preenchida. number($double)
    "dataTerceiroDesconto" => '', // Data do terceiro desconto. string($date) example: 2018-09-20T00:00:00-03:00
    "valorTerceiroDesconto" => '', // Valor do terceiro desconto.Deve ser preenchido caso a data do terceiro desconto seja preenchida. number($double)
    "tipoMulta" => 0, // Tipo de multa a ser aplicado no boleto. Informar os valores listados abaixo. - 0 Isento - 1 Valor Fixo - 2 Percentual
    "dataMulta" => '', // Deve ser maior que a data de vencimento do boleto e menor ou igual que data limite de pagamento. string($date) example: 2018-09-20T00:00:00-03:00
    "valorMulta" => '', // Valor da multa. Deve ser preenchido caso o campo dataMulta seja preenchido. 	number($double)
    "tipoJurosMora" => 3,// Tipo de juros de mora. Informar os valores listados abaixo.
    // 2 Taxa Mensal
    // 3 Isento
    "dataJurosMora" => '', // Deve ser maior que a data de vencimento do boleto e menor ou igual que data limite de pagamento. string($date) example: 2018-09-20T00:00:00-03:00
    "valorJurosMora" => '', // Valor do juros de mora. Deve ser preenchido caso o campo dataJurosMora seja preenchido. 	number($double)
    "numeroParcela" => 1,//obrigatório. Número da parcela do boleto.Valor máximo permitido '99'. integer($int64)
    "aceite" => true, // Identificador do aceite do boleto.
    "codigoNegativacao" => 3, // Código de negativação do boleto. Informar os valores abaixo. 
    // 2 Negativar Dias Úteis 
    // 3 Não Negativar
    "numeroDiasNegativacao" => '',// Número de dias para negativação do boleto. Deve ser preenchido caso o campo codigoNegativacao seja igual a '2'.
    "codigoProtesto" => 3,// Código de protesto do boleto. Informar os valores abaixo.
    // 1 Protestar Dias Corridos
    // 2 Protestar Dias Úteis
    // 3 Não Protestar
    "numeroDiasProtesto" => '',// Número de dias para protesto do boleto. Deve ser preenchido caso o campo codigoProtesto seja '1'. 	integer example: 30
    "pagador" => [
        "numeroCpfCnpj" => $numeroCpfCnpj,// CPF ou CNPJ do pagador do boleto de cobrança. Tamanho máximo 14 string
        "nome" => $nome,// Nome completo do pagador do boleto de cobrança. Tamanho máximo 50 string
        "endereco" => $endereco,// Endereço do pagador do boleto de cobrança. Tamanho máximo 40. string
        "bairro" => $bairro,// Bairro do pagador do boleto de cobrança. Tamanho máximo 30. string
        "cidade" => $cidade,//Cidade do pagador do boleto de cobrança. Tamanho máximo 40. string
        "cep" => $cep,// CEP do pagador. Tamanho máximo 8. string
        "uf" => $uf,// UF do pagador. Tamanho máximo 2. string
        "email" => [
        "",// Lista de e-mais do Pagador. string
        ]
    ],
    "beneficiarioFinal" => [
        "numeroCpfCnpj" => "98784978699",// CPF ou CNPJ do Beneficário Final. Antigo Sacador Avalista. Tamanho máximo 14. string
        "nome" => "Lucas de Lima",// Nome do Beneficário Final. Antigo Sacador Avalista. Tamanho máximo 50. string
    ],
    "mensagensInstrucao" => [
        "tipoInstrucao" => 3,// Código adotado pela FEBRABAN para identificação do tipo de impressão da mensagem do boleto de cobrança - 3 - Corpo de Instruções da Ficha de Compensação do Bloqueto
        "mensagens" => [
            "Primeiro Boleto",//Para o tipoInstrução 3 a lista permite 5 mensagens com máximo de 40 caracteres.
            "Pagar só depois que finalizar o teste",
            "Gearado via api - ERP",
        ]
    ],
    "gerarPdf" => true,// Identificador para o sistema devolver ou não o PDF do Boleto. O PDF será retornado na Base64.
    ];
    $arrayBoletos[] = $incluirBoleto;

    $reponse = $sicoob->registrarBoleto($arrayBoletos);
    print_r($reponse);
 
```