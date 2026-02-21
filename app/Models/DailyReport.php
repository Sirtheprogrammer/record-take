<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailyReport extends Model
{
    /** @use HasFactory<\Database\Factories\DailyReportFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'report_date',
        'shift',
        'site_location',
        'incidents',
        'toolbox_meeting',
        'toolbox_notes',
        'total_personnel',
        'machines_used',
        'total_working_hours',
        'work_done',
        'site_status',
        'machine_status',
        'breakdowns',
        'fuel_level',
        'maintenance_required',
        'challenges',
        'plan_for_tomorrow',
        'status',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'report_date' => 'date',
            'toolbox_meeting' => 'boolean',
            'maintenance_required' => 'boolean',
            'submitted_at' => 'datetime',
            'total_working_hours' => 'decimal:2',
            'fuel_level' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function employeeRecords(): HasMany
    {
        return $this->hasMany(EmployeeRecord::class);
    }

    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }
}
