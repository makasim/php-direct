<?php
namespace PhpDirect\Util;

use UniversalErrorCatcher_Catcher;
use PhpDirect\Response\ErrorResponse;

class ErrorCatcher
{
    protected $debug;

    protected $universalErrorCatcher;

    public function __construct($debug)
    {
        $this->debug = (Boolean) $debug;

        $this->universalErrorCatcher = new UniversalErrorCatcher_Catcher();
        $this->universalErrorCatcher->setThrowRecoverableErrors(true);
        $this->universalErrorCatcher->registerCallback(array($this, 'sendErrorResponse'));
    }

    /**
     *
     * This method can be used to convert an exception to ext,direct error response
     *
     * @param \Exception $e
     * @return \PhpDirect\Response\ErrorResponse
     */
    public function handleException(\Exception $e)
    {
        $type = '';
        $where = '';
        $message = 'Internal Server Error';
        if ($this->debug) {
            $rc = new \ReflectionClass($e);

            $message = sprintf('%s: %s', $rc->getShortName(), $e->getMessage());
            $type = $e instanceof \ErrorException ? 'error' : 'exception';
            $where = $e->getTraceAsString();
        }

        return new ErrorResponse($message, $where, $type);
    }

    /**
     *
     * This method will be called on fatal error
     *
     * @param \Exception $e
     * @return void
     */
    public function sendErrorResponse(\Exception $e)
    {
        $response = $this->handleException($e);

        $response->send();
    }

    public function start()
    {
        $this->universalErrorCatcher->start();
    }

    public function stop()
    {
        $this->universalErrorCatcher->unregisterCallback(array($this, 'handleException'));
    }
}