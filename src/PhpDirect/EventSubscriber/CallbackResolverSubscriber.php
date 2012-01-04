<?php
namespace PhpDirect\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use PhpDirect\Event\Events;
use PhpDirect\Event\SingleRequestEvent;
use PhpDirect\Service\ServiceManager;
use PhpDirect\Request\SingleRequest;

class CallbackResolverSubscriber implements EventSubscriberInterface
{
    protected $serviceManager;

    public function __construct(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public function onSingleRequest(SingleRequestEvent $event)
    {
        if ($event->getCallback()) {
            return;
        }

        $singleRequest = $event->getSingleRequest();

        $service = $singleRequest->metadata->get('action');
        $method = $singleRequest->metadata->get('method');

        $callback = $this->serviceManager->get($service, $method);
        $arguments = $this->getArguments(
            $singleRequest,
            $callback,
            $this->serviceManager->getParameters($service, $method)
        );

        $event->setCallback($callback);
        $event->setArguments($arguments);
    }

    protected function getArguments(SingleRequest $request, $controller, array $parameters)
    {
        $attributesDidgits = $request->request->all();
        array_walk($attributesDidgits, function($item, $key) use(&$attributesDidgits) {
            if (false == is_int($key)) unset($attributesDidgits[$key]);
        });

        $attributesAssocs = $request->request->all();
        array_walk($attributesDidgits, function($item, $key) use(&$attributesAssocs) {
            if (is_int($key)) unset($attributesAssocs[$key]);
        });

        $arguments = array();
        foreach ($parameters as $param) {
            if ($param->getClass() && $param->getClass()->isInstance($request)) {
                $arguments[] = $request;
            } elseif (array_key_exists($param->getName(), $attributesAssocs)) {
                $arguments[] = $attributesAssocs[$param->getName()];
            } elseif (false == empty($attributesDidgits)) {
                $arguments[] = array_shift($attributesDidgits);
            } elseif ($param->isDefaultValueAvailable()) {
                $arguments[] = $param->getDefaultValue();
            } else {
                if (is_array($controller)) {
                    $repr = sprintf('%s::%s()', get_class($controller[0]), $controller[1]);
                } elseif (is_object($controller)) {
                    $repr = get_class($controller);
                } else {
                    $repr = $controller;
                }

                throw new \RuntimeException(sprintf('Controller "%s" requires that you provide a value for the "$%s" argument (because there is no default value or because there is a non optional argument after this one).', $repr, $param->getName()));
            }
        }

        return $arguments;
    }

    public static function getSubscribedEvents()
    {
        return array(
            Events::SINGLE_REQUEST => array('onSingleRequest', 255)
        );
    }
}
 
