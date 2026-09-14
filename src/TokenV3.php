<?php

namespace Divulgueregional\apisicoob;

// use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
// use GuzzleHttp\Psr7\Message;
// use JetBrains\PhpStorm\NoReturn;

class TokenV3
{

    private $config;
    private $client;
    private $optionsRequest;
    private $escope;
    function __construct($config)
    {
        // print_r($config);
        $this->config = $config;
        $this->client = new Client([
            'base_uri' => 'https://auth.sicoob.com.br',
        ]);
        $this->optionsRequest = [
            'headers' => [
                'Accept' => 'application/x-www-form-urlencoded',
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            'cert' => $config['certificate'],
            // 'verify' => false,
            'ssl_key' => $config['certificateKey'],
        ];
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
            'scope' => $this->esope($this->config) //'cobranca_boletos_consultar'
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
            $statusCode = $response->getStatusCode();
            $responseBody = $response->getBody()->getContents();
            $responseBodyAsString = json_decode($responseBody);
            if ($responseBodyAsString == '') {
                error_log('TokenV3 ClientException - Status: ' . $statusCode . ' - Body: ' . $responseBody);
                return ['error' => 'Erro ao obter token: response body vazio (Status: ' . $statusCode . ')', 'status' => $statusCode, 'raw_body' => $responseBody];
            }
            error_log('TokenV3 ClientException - Status: ' . $statusCode . ' - Response: ' . json_encode($responseBodyAsString));
            return (array) $responseBodyAsString;
        } catch (\Exception $e) {
            $response = $e->getMessage();
            error_log('TokenV3 Exception: ' . $response);
            return ['error' => $response];
        }
    }

    private function esope($api)
    {
        if ($api['api'] == 'boleto') {
            return 'boletos_inclusao boletos_consulta boletos_alteracao webhooks_alteracao webhooks_consulta webhooks_inclusao';
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
