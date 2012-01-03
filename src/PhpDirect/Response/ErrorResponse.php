<?php
namespace PhpDirect\Response;

use Symfony\Component\HttpFoundation\Response;

class ErrorResponse extends Response
{
    public function __construct($message, $where = '', $type = '')
    {
        $content = json_encode(array(
            'type' => $where,
            'message' => $message,
            'where' => $where,
        ));

        parent::__construct($content, 500, array('Content-Type' => 'text/javascript'));
    }
}