<?php
namespace PhpDirect\Service\Definition;

use PhpDirect\Service\Provider\ProviderInterface;

class MethodDefinitionCollection implements \IteratorAggregate, \Countable
{
    protected $definitions;

    public function add(MethodDefinition $definition)
    {
        $this->definitions[] = $definition;
    }

    public function all()
    {
        return $this->definitions;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->definitions);
    }

    public function count()
    {
        return count($this->definitions);
    }
}