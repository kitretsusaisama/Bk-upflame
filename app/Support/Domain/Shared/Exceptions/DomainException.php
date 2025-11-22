<?php

namespace App\Support\Domain\Shared\Exceptions;

use Exception;

class DomainException extends Exception
{
    /**
     * Create a new domain exception instance.
     *
     * @param  string  $message
     * @param  int  $code
     * @param  \Exception|null  $previous
     * @return void
     */
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}