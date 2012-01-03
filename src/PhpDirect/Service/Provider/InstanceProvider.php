<?php
namespace PhpDirect\Service\Provider;

class InstanceProvider implements ProviderInterface
{
    /**
     * @var object
     */
    protected $service;

    /**
     * @throws \InvalidArgumentException
     * @param object $service
     */
    public function __construct($service)
    {
        if (false == is_object($service)) {
            throw new \InvalidArgumentException(sprintf('Invalid service provided should be an object but %s', gettype($service)));
        }

        $this->service = $service;
    }

    /**
     * {@inheritdoc}
     */
    public function provide()
    {
        return $this->service;
    }
}