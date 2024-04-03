<?php

namespace Divulgueregional\ApiSicoob\Exceptions;

use Divulgueregional\ApiSicoob\Exceptions\HttpClientException;

class ServiceUnavailableException extends HttpClientException
{
    const HTTP_STATUS_CODE = 503;

    public function getStatusCode()
    {
        return self::HTTP_STATUS_CODE;
    }
}