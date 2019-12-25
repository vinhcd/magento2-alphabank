<?php

namespace Monogo\Alphabank\Gateway\Http\Client;

use Zend_Http_Response;

class ResponseFactory
{
    /**
     * @param string $response
     * @return Zend_Http_Response
     */
    public function create($response)
    {
        return Zend_Http_Response::fromString($response);
    }
}
