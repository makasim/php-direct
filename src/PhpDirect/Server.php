<?php
namespace PhpDirect;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcher;

use PhpDirect\Util\ErrorCatcher;
use PhpDirect\Event\Events;
use PhpDirect\Event\MasterRequestEvent;
use PhpDirect\Event\BatchRequestEvent;
use PhpDirect\Event\SingleRequestEvent;
use PhpDirect\Event\SingleResponseEvent;
use PhpDirect\Event\BatchResponseEvent;
use PhpDirect\Event\ExceptionThrownEvent;
use PhpDirect\Response\BatchResponse;

class Server
{
    protected $eventDispatcher;

    protected $errorCatcher;

    protected $errorCather;

    public function __construct(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $masterRequest)
    {
        try {
            return $this->handleRaw($masterRequest);
        } catch (\Exception $e) {
            $event = new ExceptionThrownEvent($e);
            $this->eventDispatcher->dispatch(Events::EXCEPTION_THROW, $event);

            if ($event->getErrorResponse()) {
                return $event->getErrorResponse();
            }

            throw $e;
        }
    }

    protected function handleRaw(Request $masterRequest)
    {
        $event = new MasterRequestEvent($masterRequest);
        $this->eventDispatcher->dispatch(Events::MASTER_REQUEST, $event);
        if ($response = $event->getResponse()) {
            return $response;
        }
        if (false == $batchRequest = $event->getBatchRequest()) {
            throw new \LogicException('Master request should be parsed to a BatchRequest, Didn\'t you  forget subscribe MasterRequestParserSubscriber?');
        }

        $event = new BatchRequestEvent($batchRequest);
        $this->eventDispatcher->dispatch(Events::BATCH_REQUEST, $event);

        $singleResponses = array();
        foreach ($batchRequest as $singleRequest) {
            $event = new SingleRequestEvent($singleRequest);
            $this->eventDispatcher->dispatch(Events::SINGLE_REQUEST, $event);

            $callback = $event->getCallback();
            if (false == $callback) {
                throw new \LogicException(sprintf(
                    'The service cannot be found for action %s and method %s',
                    $singleRequest->metadata->get('action', 'undefined'),
                    $singleRequest->metadata->get('method', 'undefined')
                ));
            }

            $rawResult = call_user_func_array($callback, $event->getArguments());

            $event = new SingleResponseEvent($singleRequest, $rawResult);
            $this->eventDispatcher->dispatch(Events::SINGLE_RESPONSE, $event);

            $singleResponse = $event->getSingleResponse();
            if (false == $event->getSingleResponse()) {
                throw new \LogicException(sprintf(
                    'The raw result should be wrapped into SingleResponse, Didn\'t you forget subscribe SingleResponseWrapperSubscriber?'
                ));
            }

            $singleResponses[] = $singleResponse;
        }

        $batchResponse = new BatchResponse($singleResponses);

        $event = new BatchResponseEvent($batchResponse);
        $this->eventDispatcher->dispatch(Events::BATCH_RESPONSE, $event);

        return $batchResponse;
    }
}