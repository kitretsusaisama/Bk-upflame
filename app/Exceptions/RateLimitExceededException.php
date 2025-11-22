<?php

namespace App\Exceptions;

use Exception;

class RateLimitExceededException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @param  string  $message
     * @return void
     */
    public function __construct($message = 'Rate limit exceeded')
    {
        parent::__construct($message);
    }
}