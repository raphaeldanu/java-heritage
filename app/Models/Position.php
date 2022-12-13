<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Position extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The relationship that have to eager load.
     *
     * @var array
     */
    protected $with = ['department'];    

    /**
     * Scope a query to search the model
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilters($query, array $filters)
    {
        $query->when($filters['search'] ?? false, fn($query, $search) => 
            $query->where('name', 'like', '%' . $search . '%')
        );

        $query->when($filters['department_id'] ?? false, fn($query, $department_id) => 
            $query->where('department_id', $department_id)
        );
    }

    /**
     * Get the department that owns the Position
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
