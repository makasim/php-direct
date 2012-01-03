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
        $request = $event->getMasterRequest();

        if ('GET' == $request->getMethod() && 'api' == $request->query->get('ext')) {
            $event->setResponse($this->getApiResponse($request->getUriForPath('')));
            $event->stopPropagation();
        }
    }

    public function generateApi($endpointUrl)
    {
        $actions = array();
        foreach ($this->serviceManager->all() as $service) {
            $methods = array();
            foreach ($service->getMethods() as $method) {
                $methods[] = array('name' => $method->getAlias(), 'len' => 1);
            }

            $actions[$service->getAlias()] = $methods;
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
        $api = 'Ext.ns("'.$this->namespace.'")';
        $api = $this->namespace . 'REMOTING_API = ' . json_encode($this->generateApi($endpointUrl));

        return new Response($api, 200, array('Content-Type' => 'text/javascript'));
    }

    public static function getSubscribedEvents()
    {
        return array(Events::MASTER_REQUEST => 'onMasterRequest');
    }
}
 
