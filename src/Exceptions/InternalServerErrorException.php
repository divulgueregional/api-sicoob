<?php

namespace Divulgueregional\ApiSicoob\Exceptions;

use Divulgueregional\ApiSicoob\Exceptions\HttpClientException;

class InternalServerErrorException extends HttpClientException
{
    const HTTP_STATUS_CODE = 500;

    protected $bodyContent;

    public function getStatusCode()
    {
        return self::HTTP_STATUS_CODE;
    }

    /**
     * Get the value of bodyContent
     */
    public function getBodyContent()
    {
        return $this->bodyContent;
    }

    /**
     * Set the value of bodyContent
     */
    public function setBodyContent($bodyContent): self
    {
        $this->bodyContent = $bodyContent;

        return $this;
    }
}