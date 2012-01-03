<?php

namespace PhpDirect\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use PhpDirect\Request\BatchRequest;

class MasterRequestEvent extends Event
{
    protected $masterRequest;

    protected $batchRequest;

    protected $response;

    public function __construct(Request $masterRequest)
    {
        $this->masterRequest = $masterRequest;
    }

    public function getMasterRequest()
    {
        return $this->masterRequest;
    }

    public function setBatchRequest(BatchRequest $batchRequest)
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