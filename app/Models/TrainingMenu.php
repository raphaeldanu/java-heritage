<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrainingMenu extends Model
{
    use HasFactory;
    
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    protected $with = ['trainingSubject', 'department'];

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

        $query->when($filters['training_subject_id'] ?? false, fn($query, $training_subject_id) => 
            $query->where('training_subject_id', $training_subject_id)
        );

        $query->when($filters['department_id'] ?? false, fn($query, $department_id) => 
            $query->where('department_id', $department_id)
        );
    }

    /**
     * Get the subject that owns the TrainingMenu
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function trainingSubject(): BelongsTo
    {
        return $this->belongsTo(TrainingSubject::class);
    }

    /**
     * Get the department that owns the TrainingMenu
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get all of the trainings for the TrainingMenu
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trainings(): HasMany
    {
        return $this->hasMany(Training::class);
    }
}
