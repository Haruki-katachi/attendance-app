<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

/**
 * 勤怠記録モデル
 */
#[Fillable(['user_id', 'clockin_time', 'clockout_time'])]
class AttendanceRecord extends Model
{
    protected function casts(): array
    {
        return [
            'clockin_time' => 'datetime:Y-m-d H:i',
            'clockout_time' => 'datetime:Y-m-d H:i',
        ];
    }
}
