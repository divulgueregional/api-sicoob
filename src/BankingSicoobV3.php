<?php

namespace Divulgueregional\apisicoob;

// use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
// use GuzzleHttp\Psr7\Message;
// use JetBrains\PhpStorm\NoReturn;

// use Divulgueregional\apisicoob\Token;
require_once __DIR__ . '/TokenV3.php';

class BankingSicoobV3
{
    private $config;
    private $url;
    private $tokens;
    private $token;
    private $retornoTtoken;
    private $client;
    private $client_id;
    private $optionsRequest;

    function __construct($config)
    {
        // print_r($config);
        // die;
        $this->config = (object) $config;
        $this->url = 'https://api.sicoob.com.br';
        if ($this->config->sandbox) {
            $this->url = 'https://sandbox.sicoob.com.br/sicoob/sandbox';
            $this->token = '1301865f-c6bc-38f3-9f49-666dbcfc59c3';
            $this->client_id = '9b5e603e428cc477a2841e2683c92d21';
        } else {
            $this->tokens = new TokenV3($config);
            $this->retornoTtoken = $this->tokens->getToken();
            // print_r($this->retornoTtoken);
            // die;
            $this->token = $this->retornoTtoken['access_token'];
            $this->client_id = $config['client_id'];
        }

        $this->client = new Client([
            'base_uri' => $this->url,
        ]);
    }


    public function gerarToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getToken()
    {
        return $this->token;
    }


    ######################################################
    ############## COBRANÇAS #############################
    ######################################################
    public function registrarBoleto(array $fields)
    {
        $url = '';
        if ($this->config->sandbox) {
            $url = 'https://sandbox.sicoob.com.br/sicoob/sandbox';
        }
        try {
            $response = $this->client->request(
                'POST',
                "{$url}/cobranca-bancaria/v3/boletos",
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'client_id' => $this->client_id,
                        'Authorization' => "Bearer {$this->token}"
                    ],
                    'cert' => $this->config->certificate,
                    // 'verify' => false,
                    'ssl_key' => $this->config->certificateKey,
                    'body' => json_encode($fields),
                ]
            );
            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = json_decode($response->getBody()->getContents());
            if ($responseBodyAsString == '') {
                return ($response);
            }
            return ($responseBodyAsString);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "{$response}"];
        }
    }

    ######################################################
    ############## PDF DO BOLETO #########################
    ######################################################
    /* $params = [
        'numeroCliente' => (int) $dados->integracao_contrato,
        'codigoModalidade' => (int) 1,
        'nossoNumero' => $receber->boleto_nossoNumero,
        'linhaDigitavel' => $receber->boleto_linhaDigitavel,
        'codigoBarras' => $receber->boleto_codigoBarras,
        'gerarPdf' => true,
        'numeroContratoCobranca' => $receber->boleto_codigoCobranca,
    ];*/
    public function segundaViaBoleto($params = [])
    {
        // Configuração do ambiente
        $baseUrl = '';
        if ($this->config->sandbox) {
            $baseUrl = 'https://sandbox.sicoob.com.br/sicoob/sandbox';
        }
        // Validação dos parâmetros obrigatórios
        if (empty($params['numeroCliente'])) {
            return ['error' => 'numeroCliente é obrigatório'];
        }

        if (empty($params['codigoModalidade'])) {
            return ['error' => 'codigoModalidade é obrigatório'];
        }

        // Pelo menos um identificador do boleto deve ser informado
        $identificadores = ['nossoNumero', 'linhaDigitavel', 'codigoBarras'];
        $hasIdentifier = false;
        foreach ($identificadores as $id) {
            if (!empty($params[$id])) {
                $hasIdentifier = true;
                break;
            }
        }

        if (!$hasIdentifier) {
            return ['error' => 'Informe pelo menos um: nossoNumero, linhaDigitavel ou codigoBarras'];
        }

        try {
            $response = $this->client->request(
                'GET',
                $baseUrl . '/cobranca-bancaria/v3/boletos/segunda-via',
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'client_id' => $this->client_id,
                        'Authorization' => "Bearer {$this->token}",
                        'Accept' => 'application/json'
                    ],
                    'cert' => $this->config->certificate,
                    // 'verify' => false,
                    'ssl_key' => $this->config->certificateKey,
                    'query' => [
                        'numeroCliente' => (int) $params['numeroCliente'],
                        'codigoModalidade' => 1, //(int) 1,
                        // 'nossoNumero' => $params['nossoNumero'],
                        'linhaDigitavel' => $params['linhaDigitavel'],
                        'codigoBarras' => $params['codigoBarras'],
                        'gerarPdf' => 'true',
                        // 'numeroContratoCobranca' => (int) $params['numeroContratoCobranca']
                    ],
                    'http_errors' => true // Para tratamento manual de erros
                ]
            );

            $statusCode = $response->getStatusCode();
            $responseBody = json_decode($response->getBody()->getContents());

            // Tratamento de erros da API
            if ($statusCode >= 400) {
                $errorMessage = $responseBody->mensagens[0]->mensagem ?? 'Erro desconhecido na API Sicoob';
                $errorCode = $responseBody->mensagens[0]->codigo ?? '0000';

                return [
                    'status' => $statusCode,
                    'error' => $errorMessage,
                    'error_code' => $errorCode,
                    'api_response' => $responseBody
                ];
            }

            // Retorno de sucesso
            return [
                'status' => $statusCode,
                'data' => $responseBody
            ];
        } catch (\Exception $e) {
            // Log do erro completo para debug
            error_log('Erro na requisição de segunda via: ' . $e->getMessage());

            return [
                'error' => 'Erro na comunicação com a API Sicoob',
                'exception' => $e->getMessage()
            ];
        }
    }

    /* $params = [
        'numeroCliente' => (int) $dados->integracao_contrato,
        'codigoModalidade' => (int) 1,
        'linhaDigitavel' => $receber->boleto_linhaDigitavel,
        'codigoBarras' => $receber->boleto_codigoBarras,
        'numeroContratoCobranca' => $receber->boleto_codigoCobranca,
    ]; */
    public function consultarBoleto($params)
    {
        try {
            $response = $this->client->request(
                'GET',
                "/cobranca-bancaria/v3/boletos",
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'client_id' => $this->client_id,
                        'Authorization' => "Bearer {$this->token}",
                        'Accept' => 'application/json'
                    ],
                    'cert' => $this->config->certificate,
                    // 'verify' => false,
                    'ssl_key' => $this->config->certificateKey,
                    'query' => [
                        'numeroCliente' => (int) $params['numeroCliente'],
                        'codigoModalidade' => 1, //(int) 1,
                        'linhaDigitavel' => $params['linhaDigitavel'],
                        'codigoBarras' => $params['codigoBarras'],
                        'numeroContratoCobranca' => (int) $params['numeroContratoCobranca']
                    ],
                    'http_errors' => true // Para tratamento manual de erros
                ]
            );
            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = json_decode($response->getBody()->getContents());
            if ($responseBodyAsString == '') {
                return ($response);
            }
            return ($responseBodyAsString);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao consultar Boleto Cobranca: {$response}"];
        }
    }

    /* $params = [
        'numeroCliente' => (int) $dados->integracao_contrato,
        'codigoModalidade' => (int) 1,
        'nossoNumero' => $receber->boleto_nossoNumero,
    ]; */
    public function baixaBoleto($params)
    {
        $baseUrl =  $this->url;
        if ($this->config->sandbox) {
            $baseUrl = 'https://sandbox.sicoob.com.br/sicoob/sandbox';
        }
        $boleto = (int) $params['nossoNumero'];
        $numeroCliente = (int) $params['numeroCliente'];
        try {
            $response = $this->client->request(
                'POST',
                "{$baseUrl}/cobranca-bancaria/v3/boletos/{$boleto}/baixar",
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'client_id' => $this->client_id,
                        'Authorization' => "Bearer {$this->token}",
                        'Accept' => 'application/json'
                    ],
                    'cert' => $this->config->certificate,
                    // 'verify' => false,
                    'ssl_key' => $this->config->certificateKey,
                    'body' => json_encode([
                        'numeroCliente' => $numeroCliente,
                        'codigoModalidade' => 1
                    ]),
                    'http_errors' => true // Para tratamento manual de erros
                ]
            );
            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = json_decode($response->getBody()->getContents());
            if ($responseBodyAsString == '') {
                return ($response);
            }
            return ($responseBodyAsString);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao consultar Boleto Cobranca: {$response}"];
        }
    }

    /* $params = [
        'numeroCliente' => (int) $dados->integracao_contrato,
        'codigoModalidade' => (int) 1,
        'linhaDigitavel' => $receber->boleto_linhaDigitavel,
        'codigoBarras' => $receber->boleto_codigoBarras,
        'numeroContratoCobranca' => $receber->boleto_codigoCobranca,
    ]; */
    public function listar_boleto($params)
    {
        try {
            $response = $this->client->request(
                'GET',
                "/cobranca-bancaria/v3/pagadores/{$params['numeroCpfCnpj']}/boletos",
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'client_id' => $this->client_id,
                        'Authorization' => "Bearer {$this->token}",
                        'Accept' => 'application/json'
                    ],
                    'cert' => $this->config->certificate,
                    // 'verify' => false,
                    'ssl_key' => $this->config->certificateKey,
                    'query' => [
                        'numeroCliente' => (int) $params['numeroCliente'],
                        'codigoSituação' => 1, //1 Em Aberto - 2 Baixado - 3 Liquidado,
                        'dataInicio' => $params['dataInicio'],
                        'dataFim' => $params['dataFim'],
                        'numeroCpfCnpj' => (int) $params['numeroCpfCnpj']
                    ],
                    'http_errors' => true // Para tratamento manual de erros
                ]
            );
            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = json_decode($response->getBody()->getContents());
            if ($responseBodyAsString == '') {
                return ($response);
            }
            return ($responseBodyAsString);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao consultar Boleto Cobranca: {$response}"];
        }
    }

    public function alterarDadosBoleto(array $fields, $nossoNumero)
    {
        $url = '';
        if ($this->config->sandbox) {
            $url = 'https://sandbox.sicoob.com.br/sicoob/sandbox';
        }
        try {
            $response = $this->client->request(
                'PATCH',
                "{$url}//cobranca-bancaria/v3/boletos/{$nossoNumero}",
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'client_id' => $this->client_id,
                        'Authorization' => "Bearer {$this->token}"
                    ],
                    'cert' => $this->config->certificate,
                    // 'verify' => false,
                    'ssl_key' => $this->config->certificateKey,
                    'body' => json_encode($fields),
                ]
            );
            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = json_decode($response->getBody()->getContents());
            if ($responseBodyAsString == '') {
                return ($response);
            }
            return ($responseBodyAsString);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "{$response}"];
        }
    }

    // Função auxiliar para processar mensagens de erro
    private function parseErrorMessages($messages)
    {
        $errors = [];
        foreach ($messages as $message) {
            $errors[] = [
                'codigo' => $message->codigo ?? 'DESCONHECIDO',
                'mensagem' => $message->mensagem ?? 'Erro não especificado'
            ];
        }
        return $errors;
    }
















    ##################################################################
    ######### NÃO USAR V2 ############################################
    ##################################################################

    ######################################################
    ########## MIVIMENTAÇÃO ##############################
    ######################################################
    public function solicitarMovimentacao(array $filters)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['body'] = json_encode($filters);
        // print_r($options);die;
        try {
            $response = $this->client->request(
                'POST',
                "/cobranca-bancaria/v2/boletos/solicitacoes/movimentacao",
                $options,
            );
            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = json_decode($response->getBody()->getContents());
            if ($responseBodyAsString == '') {
                return ($response);
            }
            return ($responseBodyAsString);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao consultar Boleto Cobranca: {$response}"];
        }
    }

    public function consultarMovimentacao(array $filters)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['query'] = $filters;
        // print_r($options);die;
        try {
            $response = $this->client->request(
                'GET',
                "/cobranca-bancaria/v2/boletos/solicitacoes/movimentacao",
                $options,
            );
            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = json_decode($response->getBody()->getContents());
            if ($responseBodyAsString == '') {
                return ($response);
            }
            return ($responseBodyAsString);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao consultar Boleto Cobranca: {$response}"];
        }
    }

    public function downloadMovimentacao(array $filters)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['query'] = $filters;
        // print_r($options);die;
        try {
            $response = $this->client->request(
                'GET',
                "/cobranca-bancaria/v2/boletos/solicitacoes/movimentacao-download",
                $options,
            );
            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = json_decode($response->getBody()->getContents());
            if ($responseBodyAsString == '') {
                return ($response);
            }
            return ($responseBodyAsString);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao consultar Boleto Cobranca: {$response}"];
        }
    }

    public function saldo($filters)
    {
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['query'] = $filters;
        try {
            $response = $this->client->request(
                'GET',
                "/conta-corrente/v2/saldo",
                $options,
            );
            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) { //return $e;
            $response = $e->getResponse();
            $responseBodyAsString = json_decode($response->getBody()->getContents());
            if ($responseBodyAsString == '') {
                return ($response);
            }
            return ($responseBodyAsString);
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return ['error' => "Falha ao consultar Boleto Cobranca: {$response}"];
        }
    }

    #####################################################################
    ######## FIM - BOLETO ###############################################
    #####################################################################

    public function teste()
    {
        return 'conexão boleto sicoob realiado com sucesso';
    }
}
