<?php

namespace App\Exceptions;

use Exception;

class InvalidCredentialsException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @param  string  $message
     * @return void
     */
    public function __construct($message = 'Invalid credentials')
    {
        parent::__construct($message);
    }
}