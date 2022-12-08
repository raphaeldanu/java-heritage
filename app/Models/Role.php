<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory;

    /**
     * Scope a query to search role by name.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeSearch($query, $keyword)
    {
        $query->when($keyword ?? false, function($query, $keyword) {
            $query->where('name', 'like', '%' . $keyword . '%');
        });
    }
}
