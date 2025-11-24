<?php

namespace App\Exceptions;

use Exception;

class TenantNotFoundException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @param  string  $message
     * @return void
     */
    public function __construct($message = 'Tenant not found')
    {
        parent::__construct($message);
    }
}