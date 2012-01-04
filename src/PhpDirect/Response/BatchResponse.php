<?php
namespace PhpDirect\Response;

use Symfony\Component\HttpFoundation\Response as BaseResponse;

class BatchResponse extends BaseResponse
{
    protected $rawContent;

    protected $singleResponses;

    public function __construct(array $singleResponses)
    {
        $this->singleResponses = $singleResponses;

        if (1 === count($singleResponses)) {
            $firstSingleResponse = array_shift($singleResponses);
            $this->rawContent = $firstSingleResponse->getRawContent();
        } else {
            $this->rawContent = array();
            foreach ($singleResponses as $singleResponse) {
                $this->rawContent[] = $singleResponse->getRawContent();
            }
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

    public function getRawContent()
    {
        return $this->rawContent;
    }
}