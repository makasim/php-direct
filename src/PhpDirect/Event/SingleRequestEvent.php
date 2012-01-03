<?php

namespace PhpDirect\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

use PhpDirect\Service\Definition\ServiceDefinition;
use PhpDirect\Service\Definition\MethodDefinition;

class SingleRequestEvent extends Event
{
    protected $request;

    protected $callback;

    protected $arguments;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->arguments = array();
    }

    public function getSingleRequest()
    {
        return $this->request;
    }

    public function setServiceCallback($callback)
    {
        $this->callback = $callback;
    }

    public function getServiceCallback()
    {
        return $this->callback;
    }

    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;
    }

    public function getArguments()
    {
        return $this->arguments;
    }
}
