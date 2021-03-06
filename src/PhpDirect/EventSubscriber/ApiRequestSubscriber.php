<?php
namespace PhpDirect\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use PhpDirect\Event\Events;
use PhpDirect\Event\MasterRequestEvent;
use PhpDirect\Service\ServiceManager;

class ApiRequestSubscriber implements EventSubscriberInterface
{
    protected $serviceManager;

    protected $namespace;

    public function __construct(ServiceManager $serviceManager, $namespace = 'Ext.app')
    {
        $this->serviceManager = $serviceManager;
        $this->namespace = $namespace;
    }

    public function onMasterRequest(MasterRequestEvent $event)
    {
        if ($event->getResponse() || $event->getBatchRequest()) {
            return;
        }

        $request = $event->getMasterRequest();

        if ('GET' == $request->getMethod() && 'api' == $request->query->get('ext')) {
            $event->setResponse($this->getApiResponse($request->getUriForPath($request->getPathInfo())));
        }
    }

    public function generateApi($endpointUrl)
    {
        $actions = array();
        foreach ($this->serviceManager->all() as $serviceName => $methodsDefinitions) {
            foreach ($methodsDefinitions as $methodName => $callback) {
                $methodDef = new \stdClass();
                $methodDef->name = $methodName;

                $methodDef->len = 0;
                foreach ($this->serviceManager->getParameters($serviceName, $methodName) as $parameterReflection) {
                    $isSingleRequest =
                        $parameterReflection->getClass() &&
                        $parameterReflection->getClass()->getName() == 'PhpDirect\Request\SingleRequest'
                    ;
                    if ($isSingleRequest) continue;

                    $methodDef->len++;
                }


                if ($this->serviceManager->isFormHandler($serviceName, $methodName)) {
                    $methodDef->formHandler = true;
                }

                $methods[] = $methodDef;
            }

            $actions[$serviceName] = $methods;
        }

        return array(
            'url'=> $endpointUrl,
            'type'=> 'remoting',
            'actions'=> $actions,
            'total'=> 2200
        );
    }

    public function getApiResponse($endpointUrl)
    {
        $remotingApi = json_encode($this->generateApi($endpointUrl));
        $api = "Ext.ns('{$this->namespace}'); {$this->namespace}.REMOTING_API = {$remotingApi}";

        return new Response($api, 200, array('Content-Type' => 'text/javascript'));
    }

    public static function getSubscribedEvents()
    {
        return array(
            Events::MASTER_REQUEST => array('onMasterRequest', 255)
        );
    }
}