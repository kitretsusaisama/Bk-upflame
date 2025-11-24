<?php

namespace App\Support\Domain\Shared\ValueObjects;

use InvalidArgumentException;

class PhoneNumber
{
    private string $phoneNumber;

    public function __construct(string $phoneNumber)
    {
        // Remove any non-digit characters
        $cleanNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // Validate the phone number format
        if (strlen($cleanNumber) < 10 || strlen($cleanNumber) > 15) {
            throw new InvalidArgumentException('Invalid phone number format');
        }

        $this->phoneNumber = $cleanNumber;
    }

    public function getValue(): string
    {
        return $this->phoneNumber;
    }

    public function __toString(): string
    {
        return $this->phoneNumber;
    }
}