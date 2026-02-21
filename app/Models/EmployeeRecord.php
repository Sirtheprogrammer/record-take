<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeRecord extends Model
{
    /** @use HasFactory<\Database\Factories\EmployeeRecordFactory> */
    use HasFactory;

    protected $fillable = [
        'daily_report_id',
        'employee_name',
        'department',
        'task_performed',
        'role',
        'start_time',
        'end_time',
        'total_hours',
        'comments',
    ];

    protected function casts(): array
    {
        return [
            'total_hours' => 'decimal:2',
        ];
    }

    public function dailyReport(): BelongsTo
    {
        return $this->belongsTo(DailyReport::class);
    }
}
