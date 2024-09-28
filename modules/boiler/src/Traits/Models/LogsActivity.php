<?php

namespace Boiler\Traits\Models;

use Spatie\Activitylog\Traits\LogsActivity as SpatieLogsActivity;

trait LogsActivity
{
    use PrettyLog;
    use SpatieLogsActivity;
}
