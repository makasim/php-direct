<?php
namespace PhpDirect\Request;

class BatchRequest implements \IteratorAggregate, \Countable
{
    protected $singleRequests = array();

    public function add(SingleRequest $request)
    {
        $this->singleRequests[] = $request;
    }

    public function first()
    {
        $singleRequests = $this->singleRequests;

        return array_shift($singleRequests);
    }

    public function all()
    {
        return $this->singleRequests;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->singleRequests);
    }

    public function count()
    {
        return count($this->singleRequests);
    }
}