<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Notifications\DatabaseNotification as EloquentNotification;

class Notification extends EloquentNotification
{
    use Prunable;

    public function prunable()
    {
        return static::where('created_at', '<=', now()->subMonth(6));
    }
}
