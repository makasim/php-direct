<?php

namespace PhpDirect\Event;

use Symfony\Component\EventDispatcher\Event;

use PhpDirect\Response\Response;
use PhpDirect\Request\SingleRequest;
use PhpDirect\Response\SingleResponse;

class SingleResponseEvent extends Event
{
    protected $singleRequest;

    protected $rawResult;

    protected $singleResponse;

    public function __construct(SingleRequest $singleRequest, $rawResult)
    {
        $this->singleRequest = $singleRequest;
        $this->rawResult = $rawResult;
    }

    public function getSingleRequest()
    {
        return $this->singleRequest;
    }

    public function getRawResult()
    {
        return $this->rawResult;
    }

    public function setSingleResponse(SingleResponse $singleResponse)
    {
        $this->singleResponse = $singleResponse;
    }

    public function getSingleResponse()
    {
        return $this->singleResponse;
    }
}