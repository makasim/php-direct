<?php
namespace PhpDirect;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcher;

use PhpDirect\Util\ErrorCatcher;
use PhpDirect\Event\Events;
use PhpDirect\Event\MasterRequestEvent;
use PhpDirect\Event\BatchRequestEvent;
use PhpDirect\Event\SingleRequestEvent;

class Server
{
    protected $eventDispatcher;

    protected $errorCatcher;

    protected $errorCather;

    public function __construct(EventDispatcher $eventDispatcher, ErrorCatcher $errorCatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->errorCatcher = $errorCatcher;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $masterRequest)
    {
//        $this->errorCatcher->start();
//
//        try {
            return $this->handleRaw($masterRequest);
//        } catch (\Exception $e) {
//            return $this->errorCatcher->handleException($e);
//        }
//
//        $this->errorCatcher->stop();
    }

    protected function handleRaw(Request $masterRequest)
    {
        $event = new MasterRequestEvent($masterRequest);
        $this->eventDispatcher->dispatch(Events::MASTER_REQUEST, $event);
        if ($response = $event->getResponse()) {
            return $response;
        }
        if (false == $batchRequest = $event->getBatchRequest()) {
            throw new \LogicException('Master request should be parsed to a BatchRequest, Didn\'t you  forget subscribe RequestParser?');
        }

        $event = new BatchRequestEvent($batchRequest);
        $this->eventDispatcher->dispatch(Events::BATCH_REQUEST, $event);

        if ($response = $event->getResponse()) {
            return $response;
        }

        foreach ($batchRequest as $request) {

            $event = new SingleRequestEvent($request);
            $this->eventDispatcher->dispatch(Events::SINGLE_REQUEST, $event);

            $service = $event->getService();
            $method = $event->getMethod();
            if (false == ($service && $method)) {
                throw new \LogicException(sprintf('The service and\or method weren\'t found for the request: %s', $request));
            }



        }

        return new Response($result);
    }

    public function sendMasterRequestEvent(Request $masterRequest)
    {

    }
}