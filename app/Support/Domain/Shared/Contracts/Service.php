<?php

namespace App\Support\Domain\Shared\Contracts;

interface Service
{
    /**
     * Perform a specific business operation.
     *
     * @param  array  $data
     * @return mixed
     */
    public function handle(array $data);
}