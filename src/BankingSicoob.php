<?php

namespace Divulgueregional\apisicoob;

use Divulgueregional\ApiSicoob\Exceptions\InternalServerErrorException;
use Divulgueregional\ApiSicoob\Exceptions\InvalidRequestException;
use Divulgueregional\ApiSicoob\Exceptions\ServiceUnavailableException;
use Divulgueregional\ApiSicoob\Exceptions\UnauthorizedException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Exception;
// use GuzzleHttp\Psr7\Message;
// use JetBrains\PhpStorm\NoReturn;

// use Divulgueregional\apisicoob\Token;
require_once __DIR__ . '/Token.php';

class BankingSicoob
{
    const END_POINT_PRODUCTION = "1";
    const END_POINT_HOMOLOGATION = "2";

    private $config;
    private $token;
    private $tokens;
    private $retornoTtoken;
    protected $urls;
    protected $uriCobranca;
    protected $uriContaCorrente;
    protected $clientToken;
    protected $clientCobranca;
    protected $clientContaCorrente;
    protected $optionsRequest = [];

    function __construct($config)
    {
        $this->config = $config;
        if ($config['endPoints'] == self::END_POINT_PRODUCTION) {
            $this->urls = 'https://api.sicoob.com.br/';
        }
        if ($config['endPoints'] == self::END_POINT_HOMOLOGATION) {
            $this->urls = 'https://sandbox.sicoob.com.br/sicoob/sandbox/';
        }
        $this->uriCobranca = $this->urls . 'cobranca-bancaria/v2';
        $this->uriContaCorrente = $this->urls . 'conta-corrente/v2';
        $this->clientCobranca = new Client([
            'base_uri' => $this->uriCobranca,
        ]);
        $this->clientContaCorrente = new Client([
            'base_uri' => $this->uriContaCorrente,
        ]);
        $this->tokens = new Token($config);
        $this->retornoTtoken = $this->tokens->getToken();
        $this->token = $this->retornoTtoken['access_token'];

        $this->optionsRequest = [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'x-sicoob-clientid' => $config['client_id'],
                'client_id' => $config['client_id']
            ],
            'cert' => $config['certificate'],
            // 'verify' => false,
            'ssl_key' => $config['certificateKey'],
        ];
    }

    private function makeRequest($client, $method, $uri, $options, $errorMessage)
    {
        try {
            $response = $client->request($method, $uri, $options);
            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents());
            return array('status' => $statusCode, 'response' => $result);
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $requestParameters = $e->getRequest();
            $bodyContent = json_decode($e->getResponse()->getBody()->getContents());

            switch ($statusCode) {
                case InvalidRequestException::HTTP_STATUS_CODE:
                    $exception = new InvalidRequestException($bodyContent->erros[0]->mensagem);
                    break;
                case UnauthorizedException::HTTP_STATUS_CODE:
                    $exception = new UnauthorizedException($bodyContent->message);
                    break;
                case InternalServerErrorException::HTTP_STATUS_CODE:
                    $exception = new InternalServerErrorException($bodyContent->erros[0]->mensagem);
                    break;
                case ServiceUnavailableException::HTTP_STATUS_CODE:
                    $exception = new ServiceUnavailableException("SERVIÇO INDISPONÍVEL");
                    break;
                default:
                    $exception = $e;
                    break;
            }
            $exception->setRequestParameters($requestParameters);
            $exception->setBodyContent($bodyContent);
            throw $exception;
        } catch (Exception $e) {
            $response = $e->getMessage();
            return ['error' => "{$errorMessage}: {$response}"];
        }
    }


    public function gerarToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }


    ######################################################
    ############## COBRANÇAS #############################
    ######################################################
    public function registrarBoleto(array $fields)
    {
        $uri = "/boletos";
        $options = $this->optionsRequest;
        $options['body'] = json_encode($fields);
        $errorMessage = 'Falha ao incluir Boleto Cobranca';
        return $this->makeRequest(
            $this->clientCobranca,
            'POST',
            $uri,
            $options,
            $errorMessage
        );
    }


    public function consultarBoleto($filters)
    {
        $uri = "/boletos";
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['query'] = $filters;

        $errorMessage = 'Falha ao consultar Boleto';
        return $this->makeRequest(
            $this->clientCobranca,
            'GET',
            $uri,
            $options,
            $errorMessage
        );
    }

    public function boletoPorPagador($filters, String $numeroCpfCnpj)
    {
        $uri = "/boletos/pagadores/{$numeroCpfCnpj}";
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['query'] = $filters;

        $errorMessage = 'Falha ao buscar Boleto por pagador';
        return $this->makeRequest(
            $this->clientCobranca,
            'GET',
            $uri,
            $options,
            $errorMessage
        );
    }

    public function segundaViaBoleto($filters)
    {
        $uri = "/boletos/segunda-via";
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['query'] = $filters;

        $errorMessage = 'Falha ao obter segunda via do Boleto';
        return $this->makeRequest(
            $this->clientCobranca,
            'GET',
            $uri,
            $options,
            $errorMessage
        );
    }

    public function faixasNossoNumeroDisponivel($filters)
    {
        $uri = "/boletos/faixas-nosso-numero-disponiveis";
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['query'] = $filters;

        $errorMessage = 'Falha ao consultar faixas de Nosso Número disponíveis';
        return $this->makeRequest(
            $this->clientCobranca,
            'GET',
            $uri,
            $options,
            $errorMessage
        );
    }

    public function prorrogarDataVencimento($boletos)
    {
        $uri = "/boletos/prorrogacoes/data-vencimento";
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['body'] = json_encode($boletos);

        $errorMessage = 'Falha ao prorrogar data de vencimento do Boleto';
        return $this->makeRequest(
            $this->clientCobranca,
            'PATCH',
            $uri,
            $options,
            $errorMessage
        );
    }

    public function prorrogarDataLimite($boletos)
    {
        $uri = "/boletos/prorrogacoes/data-limite-pagamento";
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['body'] = json_encode($boletos);

        $errorMessage = 'Falha ao prorrogar data limite de pagamento do Boleto';
        return $this->makeRequest(
            $this->clientCobranca,
            'PATCH',
            $uri,
            $options,
            $errorMessage
        );
    }

    public function descontosBoleto($boletos)
    {
        $uri = "/boletos/descontos";
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['body'] = json_encode($boletos);

        $errorMessage = 'Falha ao aplicar descontos no Boleto';
        return $this->makeRequest(
            $this->clientCobranca,
            'PATCH',
            $uri,
            $options,
            $errorMessage
        );
    }

    public function abatimentosBoleto($boletos)
    {
        $uri = "/boletos/abatimentos";
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['body'] = json_encode($boletos);

        $errorMessage = 'Falha ao aplicar abatimentos no Boleto';
        return $this->makeRequest(
            $this->clientCobranca,
            'PATCH',
            $uri,
            $options,
            $errorMessage
        );
    }

    public function multaBoleto($boletos)
    {
        $uri = "/boletos/encargos/multa";
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['body'] = json_encode($boletos);

        $errorMessage = 'Falha ao aplicar multa no Boleto';
        return $this->makeRequest(
            $this->clientCobranca,
            'PATCH',
            $uri,
            $options,
            $errorMessage
        );
    }

    public function jurosMoraBoleto($boletos)
    {
        $uri = "/boletos/encargos/juros-mora";
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['body'] = json_encode($boletos);

        $errorMessage = 'Falha ao aplicar juros de mora no Boleto';
        return $this->makeRequest(
            $this->clientCobranca,
            'PATCH',
            $uri,
            $options,
            $errorMessage
        );
    }

    public function valorNominalBoleto($boletos)
    {
        $uri = "/boletos/valor-nominal";
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['body'] = json_encode($boletos);

        $errorMessage = 'Falha ao definir valor nominal do Boleto';
        return $this->makeRequest(
            $this->clientCobranca,
            'PATCH',
            $uri,
            $options,
            $errorMessage
        );
    }

    public function alterarSeuNumeroBoleto($boletos)
    {
        $uri = "/boletos/seu-numero";
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['body'] = json_encode($boletos);

        $errorMessage = 'Falha ao alterar seu número do Boleto';
        return $this->makeRequest(
            $this->clientCobranca,
            'PATCH',
            $uri,
            $options,
            $errorMessage
        );
    }

    public function especieDocumentoBoleto($boletos)
    {
        $uri = "/boletos/especie-documento";
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['body'] = json_encode($boletos);

        $errorMessage = 'Falha ao definir espécie do documento do Boleto';
        return $this->makeRequest(
            $this->clientCobranca,
            'PATCH',
            $uri,
            $options,
            $errorMessage
        );
    }


    public function baixaBoleto($boletos)
    {
        $uri = "/boletos/baixa";
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['body'] = json_encode($boletos);

        $errorMessage = 'Falha ao efetuar baixa do Boleto';
        return $this->makeRequest(
            $this->clientCobranca,
            'PATCH',
            $uri,
            $options,
            $errorMessage
        );
    }

    public function rateioCreditos($boletos)
    {
        $uri = "/boletos/rateiro-creditos";
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['body'] = json_encode($boletos);

        $errorMessage = 'Falha ao realizar rateio de créditos do Boleto';
        return $this->makeRequest(
            $this->clientCobranca,
            'PATCH',
            $uri,
            $options,
            $errorMessage
        );
    }

    public function pixBoleto($boletos)
    {
        $uri = "/boletos/pix";
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['body'] = json_encode($boletos);

        $errorMessage = 'Falha ao pagar Boleto via PIX';
        return $this->makeRequest(
            $this->clientCobranca,
            'PATCH',
            $uri,
            $options,
            $errorMessage
        );
    }

    ###################################################
    ######### PAGADOR #################################
    ###################################################
    public function alterarPagadores($boletos)
    {
        $uri = "/pagadores";
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['body'] = json_encode($boletos);

        $errorMessage = 'Falha ao alterar pagadores do Boleto';
        return $this->makeRequest(
            $this->clientCobranca,
            'PUT',
            $uri,
            $options,
            $errorMessage
        );
    }

    ######################################################
    ############## NEGATIVAÇÃO ###########################
    ######################################################
    public function negativarBoleto($boletos)
    {
        $uri = "/boletos/negativacoes";
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['body'] = json_encode($boletos);
        return $this->makeRequest(
            $this->clientCobranca,
            'POST',
            $uri,
            $options,
            "Falha ao negativar Boleto Cobranca"
        );
    }

    public function cancelarNegativarBoleto($boletos)
    {
        $uri = "/boletos/negativacoes";
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['body'] = json_encode($boletos);
        return $this->makeRequest(
            $this->clientCobranca,
            'PATCH',
            $uri,
            $options,
            "Falha ao cancelar negativação de Boleto Cobranca"
        );
    }

    public function baixarNegativarBoleto($boletos)
    {
        $uri = "/boletos/negativacoes";
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['body'] = json_encode($boletos);
        return $this->makeRequest(
            $this->clientCobranca,
            'DELETE',
            $uri,
            $options,
            "Falha ao baixar negativação de Boleto Cobranca"
        );
    }


    ######################################################
    ############## PROTESTO ##############################
    ######################################################
    public function protestarBoleto($boletos)
    {
        $uri = "/boletos/protestos";
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['body'] = json_encode($boletos);
        return $this->makeRequest(
            $this->clientCobranca,
            'POST',
            $uri,
            $options,
            "Falha ao protestar Boleto Cobranca"
        );
    }

    public function cancelarProtestoBoleto($boletos)
    {
        $uri = "/boletos/protestos";
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['body'] = json_encode($boletos);
        return $this->makeRequest(
            $this->clientCobranca,
            'PATCH',
            $uri,
            $options,
            "Falha ao cancelar protesto de Boleto Cobranca"
        );
    }

    public function desistirProtestoBoleto($boletos)
    {
        $uri = "/boletos/protestos";
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['body'] = json_encode($boletos);
        return $this->makeRequest(
            $this->clientCobranca,
            'DELETE',
            $uri,
            $options,
            "Falha ao desistir do protesto de Boleto Cobranca"
        );
    }


    ######################################################
    ########## MOVIMENTAÇÃO ##############################
    ######################################################
    public function solicitarMovimentacao(array $filters)
    {
        $uri = "/boletos/solicitacoes/movimentacao";
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['body'] = json_encode($filters);
        return $this->makeRequest(
            $this->clientCobranca,
            'POST',
            $uri,
            $options,
            "Falha ao solicitar movimentação de Boleto Cobranca"
        );
    }

    public function consultarMovimentacao(array $filters)
    {
        $uri = "/boletos/solicitacoes/movimentacao";
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['query'] = $filters;
        return $this->makeRequest(
            $this->clientCobranca,
            'GET',
            $uri,
            $options,
            "Falha ao consultar movimentação de Boleto Cobranca"
        );
    }

    public function downloadMovimentacao(array $filters)
    {
        $uri = "/boletos/solicitacoes/movimentacao-download";
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['query'] = $filters;
        return $this->makeRequest(
            $this->clientCobranca,
            'GET',
            $uri,
            $options,
            "Falha ao fazer download da movimentação de Boleto Cobranca"
        );
    }

    public function saldo($filters)
    {
        $uri = "/saldo";
        $options = $this->optionsRequest;
        $options['headers']['Authorization'] = "Bearer {$this->token}";
        $options['query'] = $filters;
        return $this->makeRequest(
            $this->clientContaCorrente,
            'GET',
            $uri,
            $options,
            "Falha ao consultar saldo da Conta Corrente"
        );
    }
}