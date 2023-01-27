<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Training extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    protected $with = ['trainingMenu', 'employees'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'training_date' => 'date:Y-m-d',
    ];

    /**
     * Scope a query to search employee
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilters($query, array $filters)
    {
        $query->when($filters['date'] ?? false, fn($query, $date) => 
            $query->where('training_date', $date)
        );

        $query->when($filters['training_menu_id'] ?? false, fn($query, $training_menu_id) => 
            $query->where('training_menu_id', $training_menu_id)
        );

        $query->when($filters['training_subject_id'] ?? false, fn($query, $training_subject_id) => 
            $query->whereHas('trainingMenu', fn($query) => 
                $query->where('training_subject_id', $training_subject_id)
            )
        );

        $query->when($filters['department_id'] ?? false, fn($query, $department_id) => 
            $query->whereHas('trainingMenu', fn($query) => 
                $query->where('department_id', $department_id)
            )
        );
    }
    
    /**
     * Get the trainingMenu that owns the Training
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function trainingMenu(): BelongsTo
    {
        return $this->belongsTo(TrainingMenu::class);
    }

    /**
     * The employees that belong to the Training
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'employee_training', 'training_id', 'employee_id');
    }
}
