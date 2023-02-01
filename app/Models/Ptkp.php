<?php

namespace App\Models;

use App\Enums\TaxStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ptkp extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'tax_status' => TaxStatus::class,
    ];

    /**
     * Scope to search based on tax status
     */
    public function scopeSearch($query, $keyword)
    {
        $query->when($keyword ?? false, fn($query, $keyword) => 
            $query->where('tax_status', 'like', '%' . $keyword . '%')
        );
    }
}
