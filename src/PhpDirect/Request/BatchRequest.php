<?php
namespace PhpDirect\Request;

use Symfony\Component\HttpFoundation\Request;

class BatchRequest implements \IteratorAggregate, \Countable
{
    protected $requests = array();

    public function first()
    {
        return $this->requests[0];
    }

    public function add(Request $request)
    {
        $this->requests[] = $request;
    }

    public function all()
    {
        return $this->requests;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->requests);
    }

    public function count()
    {
        return count($this->requests);
    }
}