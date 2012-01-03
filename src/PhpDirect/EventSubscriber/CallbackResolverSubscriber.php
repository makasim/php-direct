<?php
namespace PhpDirect\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use PhpDirect\Event\Events;
use PhpDirect\Event\SingleRequestEvent;
use PhpDirect\Service\ServiceManager;

class CallbackResolverSubscriber implements EventSubscriberInterface
{
    protected $serviceManager;

    public function __construct(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public function onSingleRequest(SingleRequestEvent $event)
    {
        $request = $event->getSingleRequest();

        $service = $request->attributes->get('action');
        $method = $request->attributes->get('method');

        $callback = $this->serviceManager->get($service, $method);

        $event->setServiceCallback($callback);
        $event->setArguments(array($event->getSingleRequest()));
    }

    public static function getSubscribedEvents()
    {
        return array(
            Events::SINGLE_REQUEST => array('onSingleRequest', 255)
        );
    }
}
 
