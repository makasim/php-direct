<?php

namespace PhpDirect\Event;

use Symfony\Component\EventDispatcher\Event;

use PhpDirect\Service\Definition\ServiceDefinition;
use PhpDirect\Service\Definition\MethodDefinition;
use PhpDirect\Request\SingleRequest;

class SingleRequestEvent extends Event
{
    protected $singleRequest;

    protected $callback;

    protected $arguments;

    public function __construct(SingleRequest $request)
    {
        $this->singleRequest = $request;
        $this->arguments = array();
        $this->callback = null;
    }

    public function getSingleRequest()
    {
        return $this->singleRequest;
    }

    public function setCallback($callback)
    {
        $this->callback = $callback;
    }

    public function getCallback()
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