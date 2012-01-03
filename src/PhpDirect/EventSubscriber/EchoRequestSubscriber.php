<?php
namespace PhpDirect\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;

use PhpDirect\Event\Events;
use PhpDirect\Event\MasterRequestEvent;

class EchoRequestSubscriber implements EventSubscriberInterface
{
    public function onMasterRequest(MasterRequestEvent $event)
    {
        $request = $event->getMasterRequest();
        if ('GET' == $request->getMethod() && 'echo' == $request->query->get('ext')) {
            $event->setResponse($this->getEchoResponse());
            $event->stopPropagation();
        }
    }

    public function getEchoResponse()
    {
        return new Response('OK');
    }

    public static function getSubscribedEvents()
    {
        return array(Events::MASTER_REQUEST => 'onMasterRequest');
    }
}
 
