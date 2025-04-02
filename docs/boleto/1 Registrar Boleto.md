# REGISTRAR BOLETO-SICOOB

## Gerar ou criar um boleto

Serviço para a inclusão de boletos informados.<br>

<!-- - É possível a inclusão de até 10 (dez) boletos por requisição. -->

```php
    require_once './../vendor/autoload.php';
    use Divulgueregional\ApiSicoob\BankingSicoobV3;

    $config = [
        'api' => 'boleto', //boleto ou pix
        'ambiente' => 'produção',
        'client_id' => $Post->cliente_id,
        'codigoBeneficiario' => $Post->contrato,
        'certificate' => './api-sicoob/path/certificado.pem',//local do certificado crt
        'certificateKey' => './api-sicoob/path/chave.pem',//local do certificado key
        'token' => '',
        'sandbox' => false // true ativa sendbox, false = producao
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

    $hoje = date('Y-m-d');
    $dadosBoleto = [
        // "numeroContrato" => 25546454,
        "numeroCliente" => (int) $this->Config['integracao_contrato'], // Número que identifica o beneficiário na plataforma de atendimento da cooperativa.
        "codigoModalidade" => 1, //1 - SIMPLES COM REGISTRO, 3 - CAUCIONADA, 4 - VINCULADA, 5 - CARNÊ DE PAGAMENTOS, 6 - INDEXADA, 8 - COBRANÇA CONTA CAPITAL
        "numeroContaCorrente" => (int) $this->Config['integracao_conta_corrente'], //'961760', //Número da Conta Corrente onde será realizado o crédito da liquidação do boleto.
        "codigoEspecieDocumento" => "DM", //DM - Duplicata Mercantil
        "dataEmissao" => $hoje, // "2025-03-20",
        // "nossoNumero" => 0, //2588658,
        "seuNumero" => $seuNumero, //"123456",
        "identificacaoBoletoEmpresa" => $seuNumero, //"4562",// (optional) Campo destinado para uso da empresa do beneficiário para identificação do boleto. Tamanho máximo 25
        "identificacaoEmissaoBoleto" => 2, //1 - Banco Emite,  2 - Cliente Emite
        "identificacaoDistribuicaoBoleto" => 2, //1 - Banco Emite,  2 - Cliente Emite
        "valor" => (floatval($resp->receber_valor)), //5.00,
        "dataVencimento" => $resp->receber_receber, //"2025-03-20",
        "dataLimitePagamento" => $resp->receber_receber, //"2025-03-20",
        // "valorAbatimento" => 1, //(optional) Valor do abatimento a ser aplicado no boleto
        "tipoDesconto" => 0, // Informar o tipo de desconto atribuido ao boleto.
        // - 0 Sem Desconto
        // - 1 Valor Fixo Até a Data Informada
        // - 2 Percentual até a data informada
        // - 3 Valor por antecipação dia corrido
        // - 4 Valor por antecipação dia útil
        // - 5 Percentual por antecipação dia corrido
        // - 6 Percentual por antecipação dia útil
        // "dataPrimeiroDesconto" => '', // (optional) Data do primeiro desconto. Formato yyyy-mm-dd. "2025-03-20",
        // "valorPrimeiroDesconto" => '', // (optional) Valor do primeiro desconto. Deve ser informado caso a data do primeiro desconto seja preenchida. 1,
        // "dataSegundoDesconto" => '', // (optional) Data do segundo desconto. Formato yyyy-mm-dd. "2025-03-20",
        // "valorSegundoDesconto" => '', // (optional) Valor do segundo desconto. Deve ser informado caso a data do segundo desconto seja preenchida. 0,
        // "dataTerceiroDesconto" => '', // (optional) Data do terceiro desconto. Formato yyyy-mm-dd. "2025-03-20",
        // "valorTerceiroDesconto" => '', //  (optional) Valor do terceiro desconto. Deve ser preenchido caso a data do terceiro desconto seja preenchida. 0,
        "tipoMulta" => 0, // Tipo de multa a ser aplicado no boleto. Informar os valores listados abaixo. - 0 Isento - 1 Valor Fixo - 2 Percentual
        // "dataMulta" => '', // (optional) Deve ser maior que a data de vencimento do boleto e menor ou igual que data limite de pagamento. Formato yyyy-mm-dd. "2025-03-20",
        // "valorMulta" => '', // (optional) Valor da multa. Deve ser preenchido o campo Data Multa seja preenchido. 5,
        "tipoJurosMora" => 3, // Tipo de juros de mora. Informar os valores listados abaixo.
        // 1 - Valor por dia
        // 2 Taxa Mensal
        // 3 Isento
        // "dataJurosMora" => "2025-03-20",// (optional) Deve ser maior que a data de vencimento do boleto e menor ou igual que data limite de pagamento. Formato yyyy-mm-dd.
        // "valorJurosMora" => 4,
        "numeroParcela" => (int) $resp->receber_parcela, // Número da parcela do boleto. Valor máximo permitido 99
        "aceite" => true, // (optional) Identificador do aceite do boleto.
        "codigoNegativacao" => 3, // (optional) Código de negativação do boleto. Informar os valores abaixo.
        // 2 - Negativar Dias Úteis
        // 3 - Não Negativar
        // "numeroDiasNegativacao" => '', //  (optional) Número de dias para negativação do boleto. Deve ser preenchido caso o campo codigoNegativacao seja igual a ‘2’. 60
        "codigoProtesto" => 3, // (optional) Código de protesto do boleto. Informar os valores abaixo.
        // 1 - Protestar Dias Corridos
        // 2 - Protestar Dias Úteis
        // 3 - Não Protestar
        // "numeroDiasProtesto" => 12,
        "pagador" => [
            "numeroCpfCnpj" => $resp->cliente_cnpj, //"98765432185",
            "nome" => $resp->cliente_nome, //"Marcelo dos Santos",
            "endereco" => $resp->cliente_rua . ', ' . $resp->cliente_nro, //"Rua 87 Quadra 1 Lote 1 casa 1",
            "bairro" => $resp->cliente_bairro, //"Santa Rosa",
            "cidade" => $resp->cliente_xMun, //"Luziânia",
            "cep" => $resp->cliente_cep, //"72320000",
            "uf" => $resp->cliente_uf, //"DF",
            // "email" => $resp->cliente_email, //"pagador@dominio.com.br"
        ],
        "beneficiarioFinal" => [
            "numeroCpfCnpj" => $resp->empresa_cnpj, //"98784978699",
            "nome" => $resp->empresa_nome //"Lucas de Lima"
        ],
        // "mensagensInstrucao" => [
        //     $mensagem1,
        //     $mensagem2,
        //     $mensagem3,
        //     $mensagem4,
        //     $mensagem5
        // ],
        // "rateioCreditos" => [
        //     [
        //         "numeroBanco" => 756,
        //         "numeroAgencia" => 4027,
        //         "numeroContaCorrente" => 0,
        //         "contaPrincipal" => true,
        //         "codigoTipoValorRateio" => 1,
        //         "valorRateio" => 100,
        //         "codigoTipoCalculoRateio" => 1,
        //         "numeroCpfCnpjTitular" => "98765432185",
        //         "nomeTitular" => "Marcelo dos Santos",
        //         "codigoFinalidadeTed" => 10,
        //         "codigoTipoContaDestinoTed" => "CC",
        //         "quantidadeDiasFloat" => 1,
        //         "dataFloatCredito" => "2020-12-30"
        //     ]
        // ],
        "codigoCadastrarPIX" => 1, // (optional) Indicar uma das opções.
        // 0 - Padrão
        // 1 - Com Pix
        // 2 - Sem Pix
        // "numeroContratoCobranca" => 1
        "gerarPdf" => true,
    ];

    $reponse = $sicoob->registrarBoleto($dadosBoleto);
    print_r($reponse);

```
