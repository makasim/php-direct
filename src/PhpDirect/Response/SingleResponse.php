<?php
namespace PhpDirect\Response;

use Symfony\Component\HttpFoundation\Response as BaseResponse;

class SingleResponse extends BaseResponse
{
    protected $rawContent;

    public function __construct($result, $tid, $action, $method, $type)
    {
        $this->rawContent = new \stdClass();

        $this->rawContent->result = $result;
        $this->rawContent->tid = $tid;
        $this->rawContent->action = $action;
        $this->rawContent->method = $method;
        $this->rawContent->type = $type;

        parent::__construct(
            json_encode($this->rawContent),
            200,
            array('Content-Type' => 'text/javascript')
        );
    }

    public function getRawContent()
    {
        return $this->rawContent;
    }
}