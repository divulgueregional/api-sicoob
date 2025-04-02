<?php

namespace Divulgueregional\apisicoob;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

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
                'Accept' => 'application/x-www-form-urlencoded'
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
            $responseBodyAsString = json_decode($response->getBody()->getContents());
            if ($responseBodyAsString == '') {
                return ($response);
            }
            return ($responseBodyAsString);
        } catch (\Exception $e) {
            $response = $e->getMessage();
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
}
