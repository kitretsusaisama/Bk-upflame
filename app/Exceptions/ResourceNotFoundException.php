<?php

namespace App\Exceptions;

use Exception;

class ResourceNotFoundException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @param  string  $message
     * @return void
     */
    public function __construct($message = 'Resource not found')
    {
        parent::__construct($message);
    }
}