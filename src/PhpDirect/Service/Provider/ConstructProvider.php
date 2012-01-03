<?php
namespace PhpDirect\Service\Provider;

class InstanceProvider implements ProviderInterface
{
    /**
     * @var string
     */
    protected $class;

    /**
     * @var array
     */
    protected $arguments;

    /**
     * @throws \InvalidArgumentException
     * @param string $class
     * @param array $arguments
     */
    public function __construct($class, array $arguments = array())
    {
        if (false == class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Given class not exists %s', $class));
        }

        $this->class = $class;
        $this->arguments = $arguments;
    }

    /**
     * {@inheritdoc}
     */
    public function provide()
    {
        $rc = new \ReflectionClass($this->class);

        return $rc->newInstance($this->arguments);
    }
}