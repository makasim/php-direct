<?php
namespace PhpDirect\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;

use PhpDirect\Event\Events;
use PhpDirect\Event\SingleRequestEvent;

class ArgumentResolverSubscriber implements EventSubscriberInterface
{
    public function onSingleRequest(SingleRequestEvent $event)
    {
        if (false == $event->getServiceCallback()) {
            throw new \RuntimeException('The service callback should be defined before argument resolving');
        }

        $arguments = $this->getArguments(
            $event->getSingleRequest(),
            $event->getServiceCallback()
        );

        $event->setArguments($arguments);
    }

    public static function getSubscribedEvents()
    {
        return array(
            Events::SINGLE_REQUEST => array('onSingleRequest', 10)
        );
    }

    protected function getArguments(Request $request, $controller)
    {
        if (is_array($controller)) {
            $r = new \ReflectionMethod($controller[0], $controller[1]);
        } elseif (is_object($controller) && !$controller instanceof \Closure) {
            $r = new \ReflectionObject($controller);
            $r = $r->getMethod('__invoke');
        } else {
            $r = new \ReflectionFunction($controller);
        }

        return $this->doGetArguments($request, $controller, $r->getParameters());
    }

    protected function doGetArguments(Request $request, $controller, array $parameters)
    {
        $attributes = get_object_vars($request->request->get(0));
        $arguments = array();
        foreach ($parameters as $param) {
            if (array_key_exists($param->getName(), $attributes)) {
                $arguments[] = $attributes[$param->getName()];
            } elseif ($param->getClass() && $param->getClass()->isInstance($request)) {
                $arguments[] = $request;
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
}