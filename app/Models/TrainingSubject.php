<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrainingSubject extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Scope a query to search department by name
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $keyword)
    {
        $query->when($keyword ?? false, fn($query, $keyword) => 
            $query->where('subject', 'like', '%' . $keyword . '%')
        );
    }

    /**
     * Get all of the menu for the TrainingSubject
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trainingMenu(): HasMany
    {
        return $this->hasMany(TrainingMenu::class);
    }
}
