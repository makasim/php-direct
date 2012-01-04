<?php
namespace PhpDirect\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use PhpDirect\Event\Events;
use PhpDirect\Event\SingleResponseEvent;
use PhpDirect\Response\SingleResponse;

class SingleResponseWrapperSubscriber implements EventSubscriberInterface
{
    public function onSingleResponse(SingleResponseEvent $event)
    {
        if ($event->getSingleResponse()) {
            return;
        }

        $singleRequest = $event->getSingleRequest();

        $event->setSingleResponse(new SingleResponse(
            $event->getRawResult(),
            $singleRequest->metadata->get('tid'),
            $singleRequest->metadata->get('action'),
            $singleRequest->metadata->get('method'),
            $singleRequest->metadata->get('type')
        ));
    }

    public static function getSubscribedEvents()
    {
        return array(
            Events::SINGLE_RESPONSE => 'onSingleResponse'
        );
    }
}