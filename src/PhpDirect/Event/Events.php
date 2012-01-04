<?php
namespace PhpDirect\Event;

abstract class Events
{
    const
        MASTER_REQUEST = 'direct.master_request',
        BATCH_REQUEST = 'direct.batch_request',
        SINGLE_REQUEST = 'direct.single_request',
        SINGLE_RESPONSE = 'direct.single_response',
        BATCH_RESPONSE = 'direct.batch_response',
        EXCEPTION_THROW = 'direct.exception_thrown'
    ;
}