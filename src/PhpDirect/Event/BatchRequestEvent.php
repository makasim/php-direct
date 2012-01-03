<?php

namespace PhpDirect\Event;

use Symfony\Component\EventDispatcher\Event;
use PhpDirect\Response\Response;

use PhpDirect\Request\BatchRequest;

class BatchRequestEvent extends Event
{
    protected $batchRequest;

    protected $response;

    public function __construct(BatchRequest $batchRequest)
    {
        $this->batchRequest = $batchRequest;
    }

    public function getBatchRequest()
    {
        return $this->batchRequest;
    }

    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }
}
