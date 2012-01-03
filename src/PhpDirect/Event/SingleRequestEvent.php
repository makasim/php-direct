<?php

namespace PhpDirect\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

use PhpDirect\Service\Definition\ServiceDefinition;
use PhpDirect\Service\Definition\MethodDefinition;

class SingleRequestEvent extends Event
{
    protected $request;

    protected $service;

    protected $method;

    public function __construct(Request $request)
    {
        $this->request;
    }

    public function getSingleRequest()
    {
        return $this->request;
    }

    public function setService(ServiceDefinition $service)
    {
        $this->service = $service;
    }

    public function getService()
    {
        return $this->service;
    }

    public function setMethod(MethodDefinition $method)
    {
        $this->method = $method;
    }

    public function getMethod()
    {
        return $this->method;
    }
}
