<?php
namespace PhpDirect\Response;

use Symfony\Component\HttpFoundation\Response;

class ErrorResponse extends Response
{
    public function __construct($message, $where = '', $type = '')
    {
        $content = new \stdClass();
        $content->type = 'exception';
        $content->message = $message;
        $content->where = $where;

        parent::__construct(json_encode($content), 500, array('Content-Type' => 'text/javascript'));
    }
}