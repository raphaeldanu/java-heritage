<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Level extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Scope a query to search level by name.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeSearch($query, $keyword)
    {
        $query->when($keyword ?? false, fn($query, $keyword) =>
            $query->where('name', 'like', '%' . $keyword . '%')
        );
    }

    /**
     * Get all of the salaryRanges for the Level
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function salaryRanges(): HasMany
    {
        return $this->hasMany(salaryRange::class);
    }
}
