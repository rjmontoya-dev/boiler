<?php

namespace Boiler\Traits\Models;

use Illuminate\Support\Facades\Auth;
use ReflectionClass;
use Spatie\Activitylog\LogOptions;

trait PrettyLog
{
    /**
     * Get activity log options
     *
     * @return \Spatie\Activitylog\LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->setDescriptionForEvent(function (string $eventName) {
                $reflection = new ReflectionClass(static::class);
                $modelName = implode(' ', preg_split('/(?<=[a-z])(?=[A-Z])|(?=[A-Z][a-z])/', $reflection->getShortName(), -1, PREG_SPLIT_NO_EMPTY));

                if (Auth::check()) {
                    return "{$modelName} #{$this->id} has been {$eventName} by " . Auth::user()->name;
                }
                return "{$modelName} #{$this->id} has been {$eventName}";

            })
            ->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }
}
