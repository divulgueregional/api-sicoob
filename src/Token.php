<?php

namespace Divulgueregional\apisicoob;

// use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
// use GuzzleHttp\Psr7\Message;
// use JetBrains\PhpStorm\NoReturn;

class Token{
    function __construct($config)
    {
        $this->config = $config;
        $this->client = new Client([
            'base_uri' => 'https://auth.sicoob.com.br',
        ]);
        $this->optionsRequest = [
            'headers' => [
                'Accept' => 'application/x-www-form-urlencoded'
            ],
            'cert' => $config['certificate'], 
            // 'verify' => false,
            'ssl_key' => $config['certificateKey'],
        ];
        $this->escope = 'cobranca_boletos_consultar';
    }

    ##############################################
    ######## TOKEN ###############################
    ############################################## cob.read cobv.write cobv.read lotecobv.write lotecobv.read pix.write pix.read webhook.read webhook.write payloadlocation.write payloadlocation.read
    public function getToken()
    {
        $options = $this->optionsRequest;
        $options['form_params'] = [
            'grant_type' => 'client_credentials',
            'client_id' => $this->config['client_id'],
            'scope' => $this->esope($this->config)//'cobranca_boletos_consultar'
        ];
        try {
            $response = $this->client->request(
                'POST',
                '/auth/realms/cooperado/protocol/openid-connect/token',
                $options
            );

            return (array) json_decode($response->getBody()->getContents());
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = json_decode($response->getBody()->getContents());
            if($responseBodyAsString==''){
                return ($response);
            }
            return ($responseBodyAsString);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => $response];
        }
    }

    private function esope($api){
        if($api['api'] == 'boleto'){
            return 'cobranca_boletos_consultar cobranca_boletos_incluir cobranca_boletos_pagador cobranca_boletos_segunda_via cobranca_boletos_descontos cobranca_boletos_abatimentos cobranca_boletos_valor_nominal cobranca_boletos_seu_numero cobranca_boletos_especie_documento cobranca_boletos_baixa cobranca_boletos_rateio_credito cobranca_pagadores cobranca_boletos_negativacoes_incluir cobranca_boletos_negativacoes_alterar cobranca_boletos_negativacoes_baixar cobranca_boletos_protestos_incluir cobranca_boletos_protestos_alterar cobranca_boletos_protestos_desistir cobranca_boletos_solicitacao_movimentacao_incluir cobranca_boletos_solicitacao_movimentacao_consultar cobranca_boletos_solicitacao_movimentacao_download cobranca_boletos_prorrogacoes_data_vencimento cobranca_boletos_prorrogacoes_data_limite_pagamento cobranca_boletos_encargos_multas cobranca_boletos_encargos_juros_mora';
        }else if($api['api'] == 'pix'){
            return 'cob.write cob.read cobv.write cobv.read lotecobv.write lotecobv.read pix.write pix.read webhook.read webhook.write payloadlocation.write payloadlocation.read';
        }
    }

    // public function gerarToken($config)
    // {
    //     $this->urlToken = 'https://auth.sicoob.com.br/auth/realms/cooperado/protocol/openid-connect/token';
    //     try {
    //         $client2 = new \GuzzleHttp\Client();
    //         $response = $client2->request('POST', $this->urlToken, [
    //             'form_params' => [
    //                 'grant_type' => 'client_credentials',
    //                 'client_id' => '48c44f4d-ff78-431d-b59d-064cef41f70c',
    //                 'scope' => 'cobranca_boletos_consultar'
    //             ],
    //             // 'cert' => '../path/certificado.pem',
    //             // 'ssl_key' => '../path/chave.pem'
    //             'cert' => $config['certificate'], 
    //             // 'verify' => false,
    //             'ssl_key' => $config['certificateKey'],
    //         ]);
    //         $this->token = $response->getBody()->getContents();
    //         $this->timeToken = time();
    //         return $this->token;
    //     } catch (\Exception $exc) {
    //         throw $exc;
    //     }
    // }
} 
