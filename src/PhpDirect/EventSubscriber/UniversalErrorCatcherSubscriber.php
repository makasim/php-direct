<?php
namespace PhpDirect\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use PhpDirect\Event\Events;
use PhpDirect\Event\MasterRequestEvent;
use PhpDirect\Event\BatchResponseEvent;
use PhpDirect\Event\ExceptionThrownEvent;

use PhpDirect\Response\ErrorResponse;

class UniversalErrorCatcherSubscriber implements EventSubscriberInterface
{
    public function __construct($debug)
    {
        $this->debug = (Boolean) $debug;

        $this->universalErrorCatcher = new \UniversalErrorCatcher_Catcher();
        $this->universalErrorCatcher->setThrowRecoverableErrors(true);
    }

    public function onMasterRequest(MasterRequestEvent $event)
    {
        $this->universalErrorCatcher->registerCallback(array($this, 'handleException'));
        $this->universalErrorCatcher->start();
    }

    public function onBatchResponse(BatchResponseEvent $event)
    {
        $this->universalErrorCatcher->unregisterCallback(array($this, 'handleException'));
    }

    public function onExceptionThrown(ExceptionThrownEvent $event)
    {
        $this->universalErrorCatcher->unregisterCallback(array($this, 'handleException'));

        $event->setErrorResponse($this->handleException($event->getException()));
    }

    public function handleException($e)
    {
        $type = '';
        $where = '';
        $message = 'Internal Server Error';
        if ($this->debug) {
            $rc = new \ReflectionClass($e);

            $message = sprintf('%s: %s', $rc->getShortName(), $e->getMessage());
            $type = $e instanceof \ErrorException ? 'error' : 'exception';
            $where = $e->getTraceAsString();
        }

        $errorResponse = new ErrorResponse($message, $where, $type);

        if ($e instanceof \FatalErrorException) {
            $errorResponse->send();
        }

        return $errorResponse;
    }

    public static function getSubscribedEvents()
    {
        return array(
            Events::MASTER_REQUEST => array('onMasterRequest', 100000),
            Events::BATCH_RESPONSE => array('onBatchResponse', -100000),
            Events::EXCEPTION_THROW => 'onExceptionThrown',
        );
    }

    protected function start()
    {
        $this->universalErrorCatcher->start();
    }
}