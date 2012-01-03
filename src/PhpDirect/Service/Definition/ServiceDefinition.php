<?php
namespace PhpDirect\Service\Definition;

use PhpDirect\Service\Provider\ProviderInterface;

class ServiceDefinition
{
    /**
     * @var string
     */
    protected $alias;

    /**
     * @var \PhpDirect\Service\Provider\ProviderInterface
     */
    protected $serviceProvider;

    /**
     * @param string $name
     * @param Provider\ProviderInterface $serviceProvider
     */
    public function __construct(MethodDefinitionCollection $methods, ProviderInterface $serviceProvider, $alias)
    {
        $this->methods = $methods;
        $this->serviceProvider = $serviceProvider;
        $this->alias = $alias;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @return \PhpDirect\Service\Definition\MethodDefinitionCollection
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @return \PhpDirect\Service\Provider\ProviderInterface
     */
    public function getProvider()
    {
        return $this->serviceProvider;
    }
}