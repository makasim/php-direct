<?php
namespace PhpDirect\Service\Definition;

use PhpDirect\Service\Provider\ProviderInterface;

class MethodDefinition
{
    protected $name;

    protected $alias;

    /**
     * @param string $name
     * @param Provider\ProviderInterface $serviceProvider
     */
    public function __construct($name, $alias = null)
    {
        $this->name = $name;
        $this->alias = $alias ?: $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getAlias()
    {
        return $this->alias;
    }
}