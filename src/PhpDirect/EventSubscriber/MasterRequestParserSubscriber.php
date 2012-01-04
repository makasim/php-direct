<?php
namespace PhpDirect\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;

use PhpDirect\Event\Events;
use PhpDirect\Event\MasterRequestEvent;

use PhpDirect\Request\BatchRequest;
use PhpDirect\Request\SingleRequest;

class MasterRequestParserSubscriber implements EventSubscriberInterface
{
    public function onMasterRequest(MasterRequestEvent $event)
    {
        if ($event->getResponse() || $event->getBatchRequest()) {
            return;
        }

        if ($batchRequest = $this->parseBatchRequest($event->getMasterRequest())) {
            $event->setBatchRequest($batchRequest);
        }
    }

    /**
     * @throws \LogicException
     * @param \Symfony\Component\HttpFoundation\Request $masterRequest
     * @return BatchRequest
     */
    public function parseBatchRequest(Request $masterRequest)
    {
        if ($batchRequest = $this->parseFormPost($masterRequest)) {
            return $batchRequest;
        }
        if ($batchRequest = $this->parseRawPost($masterRequest)) {
            return $batchRequest;
        }

        return false;
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
            $metadata = array(
                'type' => $singleRawRequest->type,
                'tid' =>  $singleRawRequest->tid,
                'upload' => false,
                'action' => $singleRawRequest->action,
                'method' => $singleRawRequest->method,
            );

            $request = array_values($singleRawRequest->data);

            $batchRequest->add(new SingleRequest($masterRequest, $metadata, $request));
        }

        return $batchRequest;
    }

    protected function parseFormPost(Request $masterRequest)
    {
        $isValid = 'POST' == $masterRequest->getMethod() && $masterRequest->get('extAction') && $masterRequest->get('extMethod');
        if (false == $isValid) {
            return;
        }

        $metadata = array(
            'tid' =>  $masterRequest->get('extTID'),
            'type' => $masterRequest->get('extType'),
            'upload' => $masterRequest->get('extUpload'),
            'action' => $masterRequest->get('extAction'),
            'method' => $masterRequest->get('extMethod'),
        );

        $request = array($masterRequest->request->all());
        unset(
            $request[0]['extTID'],
            $request[0]['extType'],
            $request[0]['extUpload'],
            $request[0]['extAction'],
            $request[0]['extMethod']
        );
        $request[0] = (object) $request[0];

        $singleRequest = new SingleRequest($masterRequest, $metadata, $request);

        $batchRequest = new BatchRequest();
        $batchRequest->add($singleRequest);

        return $batchRequest;
    }

    public static function getSubscribedEvents()
    {
        $lowPriority = -255;

        return array(Events::MASTER_REQUEST => 'onMasterRequest', $lowPriority);
    }
}