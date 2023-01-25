<?php

namespace App\Models;

use Carbon\Carbon;
use App\Enums\Gender;
use App\Enums\TaxStatus;
use App\Enums\EmploymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Employee extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    
    protected $with = ['position'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'employment_status' => EmploymentStatus::class,
        'gender' => Gender::class,
        'tax_status' => TaxStatus::class,
        'first_join' => 'date:Y-m-d',
        'last_contract_start' => 'date:Y-m-d',
        'last_contract_end' => 'date:Y-m-d',
        'birth_date' => 'date:Y-m-d',
    ];

    /**
     * Scope a query to search employee
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilters($query, array $filters)
    {
        $query->when($filters['search'] ?? false, fn($query, $search) => 
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('nip', 'like', '%' . $search . '%')
        );

        $query->when($filters['level_id'] ?? false, fn($query, $level_id) => 
            $query->where('level_id', $level_id)
        );

        $query->when($filters['department_id'] ?? false, fn($query, $department_id) => 
            $query->whereHas('position', fn($query) => 
                $query->where('department_id', $department_id)
            )
        );
    }
    
    /**
     * Get the user that owns the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the position that owns the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    /**
     * Get the salaryRange that owns the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function salaryRange(): BelongsTo
    {
        return $this->belongsTo(SalaryRange::class);
    }

    /**
     * Get the residence associated with the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function residence(): HasOne
    {
        return $this->hasOne(ResidenceAddress::class);
    }

    /**
     * Get the leave associated with the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function leave(): HasOne
    {
        return $this->hasOne(Leave::class);
    }

    /**
     * Get all of the families for the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function families(): HasMany
    {
        return $this->hasMany(Family::class);
    }

    /**
     * Get all of the leaveRequests for the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    /**
     * Get the department associated with the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function department(): HasOneThrough
    {
        return $this->hasOneThrough(Department::class, Position::class, 'id', 'id', 'position_id', 'department_id');
    }
}
