<?php

namespace PhpDirect\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\FileBag;

use PhpDirect\Service\Definition\ServiceDefinition;
use PhpDirect\Service\Definition\MethodDefinition;

class ServiceEvent extends Event
{
    protected $arguments;

    protected $files;

    protected $service;

    protected $method;

    public function __construct(ServiceDefinition $service, MethodDefinition $method, ParameterBag $arguments, FileBag $files)
    {

    }
}
