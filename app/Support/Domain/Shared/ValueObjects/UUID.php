<?php

namespace App\Support\Domain\Shared\ValueObjects;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid as RamseyUuid;

class UUID
{
    private string $uuid;

    public function __construct(?string $uuid = null)
    {
        if ($uuid === null) {
            $this->uuid = RamseyUuid::uuid4()->toString();
        } else {
            if (!RamseyUuid::isValid($uuid)) {
                throw new InvalidArgumentException('Invalid UUID format');
            }
            $this->uuid = $uuid;
        }
    }

    public function getValue(): string
    {
        return $this->uuid;
    }

    public function __toString(): string
    {
        return $this->uuid;
    }
    
    public function equals(UUID $other): bool
    {
        return $this->uuid === $other->uuid;
    }
}