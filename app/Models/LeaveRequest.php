<?php

namespace App\Models;

use App\Enums\LeaveStatus;
use App\Enums\LeaveType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    protected $with = ['employee:id,user_id,name'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'leave_type' => LeaveType::class,
        'status' => LeaveStatus::class,
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
    ];

    /**
     * Scope a query to filter search
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilters($query, array $filters)
    {
        $query->when($filters['search'] ?? false, fn($query, $search) => 
            $query->whereHas('employee', fn($query) => $query->where('name', 'like', '%' . $search . '%'))
                  ->orWhere('note', 'like', '%' . $search . '%')
        );

        $query->when($filters['status'] ?? false, fn($query, $status) => 
            $query->where('status', $status)
        );

        $query->when($filters['type'] ?? false, fn($query, $type) => 
            $query->where('leave_type', $type)
        );
        
    }

    /**
     * Scope a query to only include by department
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDepartment($query, $department_id)
    {
        $query->whereHas('employee', fn($query, ...$department_id) => 
            $query->whereHas('position', fn($query) => 
                $query->where('department_id', $department_id)
            )    
        );
    }

    /**
     * Get the employee that owns the LeaveRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the approver that owns the LeaveRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
