<?php

namespace PhpDirect\Event;

use Symfony\Component\EventDispatcher\Event;

use PhpDirect\Response\BatchResponse;

class BatchResponseEvent extends Event
{
    protected $batchResponse;

    public function __construct(BatchResponse $batchResponse)
    {
        $this->batchResponse = $batchResponse;
    }

    public function getBatchResponse()
    {
        return $this->batchResponse;
    }
}