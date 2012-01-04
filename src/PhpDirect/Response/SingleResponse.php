<?php
namespace PhpDirect\Response;

use Symfony\Component\HttpFoundation\Response as BaseResponse;

class SingleResponse extends BaseResponse
{
    public function __construct($rawContent, $tid, $action, $method, $type)
    {
        $content = new \stdClass();
        $content->type = $type;
        $content->action = $action;
        $content->method = $method;
        $content->type = $type;
        $content->result = $rawContent;

        parent::__construct($content, 200, array('Content-Type' => 'text/javascript'));
    }

    public function sendContent()
    {
        echo json_encode($this->getContent());
    }
}