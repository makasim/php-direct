<?php

namespace PhpDirect\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

use PhpDirect\Response\Response;

class SingleResponseEvent extends Event
{
    protected $singleRequest;

    protected $rawResponse;

    public function __construct(Request $singleRequest, $rawResponse)
    {
        $this->singleRequest = $singleRequest;
        $this->rawResponse = $rawResponse;
    }

    public function getSingleRequest()
    {
        return $this->singleRequest;
    }

    public function getRawResponse()
    {
        return $this->rawResponse;
    }

    public function setResponse()
    {

    }
}
 
