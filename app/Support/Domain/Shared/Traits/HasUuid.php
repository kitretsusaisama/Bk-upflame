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
                // Add some debugging to see if this is working
                \Illuminate\Support\Facades\Log::info('HasUuid: Setting ' . $uuidColumn . ' to ' . $uuid . ' for model ' . get_class($model));
            } else {
                \Illuminate\Support\Facades\Log::info('HasUuid: ' . $uuidColumn . ' already set to ' . $model->$uuidColumn . ' for model ' . get_class($model));
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