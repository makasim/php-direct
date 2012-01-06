<?php
namespace PhpDirect\Response;

use Symfony\Component\HttpFoundation\Response as BaseResponse;

class SingleUploadResponse extends SingleResponse
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

        BaseResponse::__construct(
            '<html><body><textarea>' . json_encode($this->rawContent) . '</textarea></body></html>',
            200,
            array('Content-Type' => 'text/html')
        );
    }

    public function getRawContent()
    {
        return $this->rawContent;
    }
}