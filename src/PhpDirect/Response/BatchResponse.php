<?php
namespace PhpDirect\Response;

use Symfony\Component\HttpFoundation\Response as BaseResponse;

class BatchResponse extends BaseResponse implements \Countable
{
    protected $rawContent;

    protected $singleResponses;

    public function __construct(array $singleResponses)
    {
        $this->singleResponses = $singleResponses;

        $this->rawContent = array();
        foreach ($this->singleResponses as $singleResponse) {
            $this->rawContent[] = $singleResponse->getRawContent();
        }

        parent::__construct(
            json_encode($this->rawContent),
            200,
            array('Content-Type' => 'text/javascript')
        );
    }

    public function first()
    {
        $singleResponses = $this->singleResponses;

        return array_shift($singleResponses);
    }

    public function count()
    {
        return count($this->singleResponses);
    }

    public function getRawContent()
    {
        return $this->rawContent;
    }
}