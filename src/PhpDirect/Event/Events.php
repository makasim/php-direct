<?php
namespace PhpDirect\Event;

abstract class Events
{
    const
        MASTER_REQUEST = 'direct.master_request',
        BATCH_REQUEST = 'direct.batch_request',
        SINGLE_REQUEST = 'direct.single_request',
        SERVICE = 'direct.service',
        RESPONSE = 'direct.response';
}