<?php

namespace App\Support\Domain\Shared\Traits;

use App\Support\Domain\Shared\ValueObjects\UUID;
use Illuminate\Support\Str;

trait HasUuid
{
    protected static function bootHasUuid()
    {
        static::creating(function ($model) {
            // Check if the UUID column exists and is empty
            $uuidColumn = $model->getUuidColumn();
            if (!isset($model->$uuidColumn) || empty($model->$uuidColumn)) {
                $uuid = Str::uuid()->toString();
                $model->$uuidColumn = $uuid;
            }
        });
    }

    public function getUuidColumn(): string
    {
        return property_exists($this, 'uuidColumn') ? $this->uuidColumn : 'uuid';
    }

    public function getUuid(): string
    {
        return $this->{$this->getUuidColumn()};
    }
}