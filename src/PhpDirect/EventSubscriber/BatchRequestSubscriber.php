<?php
namespace PhpDirect\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;

use PhpDirect\Event\Events;
use PhpDirect\Event\MasterRequestEvent;
use PhpDirect\Request\RequestParser;

class BatchRequestSubscriber implements EventSubscriberInterface
{
    protected $requestParser;

    public function __construct(RequestParser $requestParser)
    {
        $this->requestParser = $requestParser;
    }

    public function onMasterRequest(MasterRequestEvent $event)
    {
        $batchRequest = $this->requestParser->parse($event->getMasterRequest());

        $event->setBatchRequest($batchRequest);
        $event->stopPropagation();
    }

    public static function getSubscribedEvents()
    {
        $lowPriority = -255;

        return array(Events::MASTER_REQUEST => 'onMasterRequest', $lowPriority);
    }
}
 
