<?php
namespace PhpDirect\Response;

use Symfony\Component\HttpFoundation\Response as BaseResponse;

class Response extends BaseResponse
{
    public function __construct($content)
    {
        parent::__construct($content, 200, array('Content-Type' => 'text/javascript'));
    }

    public function sendContent()
    {
        echo
            '<html><body><textarea>',
            json_encode($this->getContent()),
            '</textarea></body></html>';
    }
}