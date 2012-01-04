<?php
namespace PhpDirect\Service;

use PhpDirect\Service\Definition\ServiceDefinition;

class ServiceManager
{
    protected $services = array();

    protected $form_handlers;

    public function add($service, $method, $callback)
    {
        $this->services[$service][$method] = $callback;

        return $this;
    }

    public function remove($service, $method)
    {
        unset($this->services[$service][$method]);

        return $this;
    }

    public function all()
    {
        return $this->services;
    }

    public function get($service, $method)
    {
        if (false == isset($this->services[$service][$method])) {
            throw new \InvalidArgumentException(sprintf(
                'There is no service definition with such service  %s and %s method names',
                $service,
                $method
            ));
        }

        return $this->services[$service][$method];
    }

    public function setFormHandler($service, $method, $boolean = true)
    {
        if ($boolean) {
            $this->form_handlers[$service][$method] = $boolean;
        } else {
            unset($this->form_handlers[$service][$method]);
        }
    }

    public function isFormHandler($service, $method)
    {
        return isset($this->form_handlers[$service][$method]);
    }

    /**
     * @param string $service
     * @param string $method
     *
     * @return array
     */
    public function getParameters($service, $method)
    {
        $controller = $this->get($service, $method);

        if (is_array($controller)) {
            $r = new \ReflectionMethod($controller[0], $controller[1]);
        } elseif (is_object($controller) && !$controller instanceof \Closure) {
            $r = new \ReflectionObject($controller);
            $r = $r->getMethod('__invoke');
        } else {
            $r = new \ReflectionFunction($controller);
        }

        return $r->getParameters();
    }
}