<?php

namespace Divulgueregional\apisicoob;

use Exception;
use Divulgueregional\ApiSicoob\Exceptions\InternalServerErrorException;
use Divulgueregional\ApiSicoob\Exceptions\InvalidRequestException;
use Divulgueregional\ApiSicoob\Exceptions\ServiceUnavailableException;
use Divulgueregional\ApiSicoob\Exceptions\UnauthorizedException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
// use GuzzleHttp\Psr7\Message;
// use JetBrains\PhpStorm\NoReturn;

class Token
{
    const BASE_URI = 'https://auth.sicoob.com.br';
    const TOKEN_ENDPOINT = '/auth/realms/cooperado/protocol/openid-connect/token';

    private $config;
    private $client;
    private $optionsRequest;
    private $escope;

    function __construct($config)
    {
        $this->config = $config;
        $this->client = new Client([
            'base_uri' => self::BASE_URI,
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

    private function makeRequest($method, $uri, $options, $errorMessage)
    {
        try {
            $response = $this->client->request($method, $uri, $options);
            return (array) json_decode($response->getBody()->getContents());
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $requestParameters = $e->getRequest();
            $bodyContent = json_decode($e->getResponse()->getBody()->getContents());

            switch ($statusCode) {
                case InvalidRequestException::HTTP_STATUS_CODE:
                    $exception = new InvalidRequestException($bodyContent->erros[0]->mensagem);
                    $exception->setRequestParameters($requestParameters);
                    $exception->setBodyContent($bodyContent);
                    throw $exception;
                case UnauthorizedException::HTTP_STATUS_CODE:
                    $exception = new UnauthorizedException($bodyContent->message);
                    $exception->setRequestParameters($requestParameters);
                    $exception->setBodyContent($bodyContent);
                    throw $exception;
                case InternalServerErrorException::HTTP_STATUS_CODE:
                    $exception = new InternalServerErrorException($bodyContent->erros[0]->mensagem);
                    $exception->setRequestParameters($requestParameters);
                    $exception->setBodyContent($bodyContent);
                    throw $exception;
                case ServiceUnavailableException::HTTP_STATUS_CODE:
                    $exception = new ServiceUnavailableException("SERVIÇO INDISPONÍVEL");
                    $exception->setRequestParameters($requestParameters);
                    throw $exception;
                default:
                    throw $e;
            }
        } catch (Exception $e) {
            $response = $e->getMessage();
            return ['error' => "{$errorMessage}: {$response}"];
        }
    }

    ##############################################
    ######## TOKEN ###############################
    ##############################################
    public function getToken()
    {
        $options = $this->optionsRequest;
        $options['form_params'] = [
            'grant_type' => 'client_credentials',
            'client_id' => $this->config['client_id'],
            'scope' => $this->esope($this->config) //'cobranca_boletos_consultar'
        ];
        return $this->makeRequest('POST', self::TOKEN_ENDPOINT, $options, "Falha ao gerar Token");
    }

    private function esope($api)
    {
        if ($api['api'] == 'boleto') {
            return 'cobranca_boletos_consultar cobranca_boletos_incluir cobranca_boletos_pagador cobranca_boletos_segunda_via cobranca_boletos_descontos cobranca_boletos_abatimentos cobranca_boletos_valor_nominal cobranca_boletos_seu_numero cobranca_boletos_especie_documento cobranca_boletos_baixa cobranca_boletos_rateio_credito cobranca_pagadores cobranca_boletos_negativacoes_incluir cobranca_boletos_negativacoes_alterar cobranca_boletos_negativacoes_baixar cobranca_boletos_protestos_incluir cobranca_boletos_protestos_alterar cobranca_boletos_protestos_desistir cobranca_boletos_solicitacao_movimentacao_incluir cobranca_boletos_solicitacao_movimentacao_consultar cobranca_boletos_solicitacao_movimentacao_download cobranca_boletos_prorrogacoes_data_vencimento cobranca_boletos_prorrogacoes_data_limite_pagamento cobranca_boletos_encargos_multas cobranca_boletos_encargos_juros_mora';
        } else if ($api['api'] == 'pix') {
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
