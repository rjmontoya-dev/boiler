<?php

namespace Boiler\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Boiler\Traits\Models\LogsActivity;

class BaseModel extends Model
{
    use HasFactory;
    use LogsActivity;
}
