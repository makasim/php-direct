<?php

namespace PhpDirect\Event;

use Symfony\Component\EventDispatcher\Event;

use PhpDirect\Response\ErrorResponse;

class ExceptionThrownEvent extends Event
{
    protected $exception;

    protected $errorResponse;

    public function __construct(\Exception $exception)
    {
        $this->exception = $exception;
    }

    public function getException()
    {
        return $this->exception;
    }

    public function setErrorResponse(ErrorResponse $errorResponse)
    {
        $this->errorResponse = $errorResponse;
    }

    public function getErrorResponse()
    {
        return $this->errorResponse;
    }
}