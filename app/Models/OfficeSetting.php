<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'office_name',
        'latitude',
        'longitude',
        'radius_meter',
        'work_start_time',
        'work_end_time',
        'effective_work_days',
    ];

    protected function casts(): array
    {
        return [
            'work_start_time' => 'string',
            'work_end_time' => 'string',
            'effective_work_days' => 'integer',
        ];
    }
}
