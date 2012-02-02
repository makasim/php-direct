<?php
namespace PhpDirect\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use PhpDirect\Event\Events;
use PhpDirect\Event\SingleRequestEvent;
use PhpDirect\Event\MasterRequestEvent;
use PhpDirect\Service\ServiceManager;
use PhpDirect\Request\SingleRequest;
use PhpDirect\Request\Argument\FormBag;

class FormHandlerResolverSubscriber implements EventSubscriberInterface
{
    protected $serviceManager;

    public function __construct(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public function onMasterRequest(MasterRequestEvent $event)
    {
        if ($event->getBatchRequest() || $event->getResponse()) {
            return;
        }

        $request = $event->getMasterRequest();
        if (false == ('GET' == $request->getMethod() && 'api' == $request->query->get('ext'))) {
            return;
        }

        foreach($this->serviceManager->all() as $serviceName => $methods) {
            foreach($methods as $methodName => $callback) {
                $parameters = $this->serviceManager->getParameters($serviceName, $methodName);
                if (false !== $this->getFormArgumentPosition($parameters)) {
                    $this->serviceManager->markFormHandler($serviceName, $methodName);
                }
            }
        }
    }

    public function onSingleRequest(SingleRequestEvent $event)
    {
        if (false == $event->getCallback()) {
            return;
        }

        $singleRequest = $event->getSingleRequest();

        $service = $singleRequest->metadata->get('action');
        $method = $singleRequest->metadata->get('method');

        $formArgumentPosition = $this->getFormArgumentPosition(
            $this->serviceManager->getParameters($service, $method)
        );
        if (false !== $formArgumentPosition) {
            $arguments = $event->getArguments();
            $arguments[$formArgumentPosition] = new FormBag((array) $arguments[$formArgumentPosition]);

            $event->setArguments($arguments);
        }
    }

    protected function getFormArgumentPosition(array $parameters)
    {
        foreach ($parameters as $position => $parameter) {
            $isFormArgument =
                $parameter->getClass() &&
                $parameter->getClass()->getName() == 'PhpDirect\Request\Argument\FormBag'
            ;

            if ($isFormArgument) {
                return $position;
            }
        }


        return false;
    }

    public static function getSubscribedEvents()
    {
        return array(
            Events::SINGLE_REQUEST => array('onSingleRequest', 200),
            Events::MASTER_REQUEST => array('onMasterRequest', 300)
        );
    }
}
 
