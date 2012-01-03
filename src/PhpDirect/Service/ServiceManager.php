<?php
namespace PhpDirect\Service;

use PhpDirect\Service\Definition\ServiceDefinition;

class ServiceManager
{
    protected $services = array();

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
}