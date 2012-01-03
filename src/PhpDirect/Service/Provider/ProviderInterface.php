<?php
namespace PhpDirect\Service\Provider;

interface ProviderInterface
{
    /**
     * @abstract
     *
     * @return object
     */
    function provide();
}