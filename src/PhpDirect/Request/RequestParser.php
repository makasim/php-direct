<?php
namespace PhpDirect\Request;

use Symfony\Component\HttpFoundation\Request;

class RequestParser
{
    /**
     * @throws \LogicException
     * @param \Symfony\Component\HttpFoundation\Request $masterRequest
     * @return BatchRequest
     */
    public function parse(Request $masterRequest)
    {
        $batchRequest = $this->parseFormPost($masterRequest);
        $batchRequest = $batchRequest ?: $this->parseRawPost($masterRequest);

        if (false == $batchRequest) {
            throw new \LogicException('Cannot parse request: '.$masterRequest);
        }

        return $batchRequest;
    }

    protected function parseRawPost(Request $masterRequest)
    {
        if ('POST' != $masterRequest->getMethod()) {
            return;
        }
        if (false == $rawRequest = $masterRequest->getContent()) {
            return;
        }
        if (false == $rawRequest = json_decode($rawRequest)) {
            return;
        }

        $batchRequest = new BatchRequest();
        is_array($rawRequest) || $rawRequest = array($rawRequest);
        foreach ($rawRequest as $singleRawRequest) {

            $request = clone $masterRequest;

            $request->attributes->set('tid', $singleRawRequest->tid);
            $request->attributes->set('action', $singleRawRequest->action);
            $request->attributes->set('method', $singleRawRequest->method);
            $request->request->replace($singleRawRequest->data);

            $batchRequest->add($request);
        }

        return $batchRequest;
    }

    protected function parseFormPost(Request $masterRequest)
    {
        $isValid = 'POST' == $masterRequest->getMethod() && $masterRequest->get('extAction') && $masterRequest->get('extMethod');
        if (false == $isValid) {
            return;
        }

        $request = clone $masterRequest;

        $request->attributes->set('tid', $masterRequest->get('extTid'));
        $request->attributes->set('action', $masterRequest->get('extAction'));
        $request->attributes->set('method', $masterRequest->get('extMethod'));

        $request->request->remove('extTid');
        $request->request->remove('extAction');
        $request->request->remove('extMethod');

        return new BatchRequest($request);
    }
}