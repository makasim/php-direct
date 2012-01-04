<?php
namespace PhpDirect\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;

class SingleRequest
{
    public $masterRequest;

    public $metadata;

    public $request;

    public function __construct(Request $masterRequest, array $metadata, array $request)
    {
        $this->masterRequest = $masterRequest;
        $this->metadata = new ParameterBag($metadata);
        $this->request = new ParameterBag($request);
    }
}