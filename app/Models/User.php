<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'active' => 'boolean',
    ];
    
    public function changeStatus()
    {
        if ($this->active){
            $this->active = false;
        } else {
            $this->active = true;
        }
        $this->save();
    }

    /**
     * Scope a query to filter when searching
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilters($query, array $filters)
    {
        $query->when($filters['search'] ?? false, fn($query, $search) =>
            $query->where('username', 'like', '%' . $search . '%')
        );

        $query->when($filters['status'] ?? false, fn ($query, $status) =>
            $query->where('active', $status=='active' ? 1 : 0)
        );

        $query->when($filters['role'] ?? false, fn($query, $role) =>
            $query->whereHas('roles', fn($query) => 
                $query->where('id', $role)
            )
        );
    }

    /**
     * Get the role
     *
     * @return Role
     */
    public function role()
    {
        return $this->roles()->first();
    }

    /**
     * Get the employee associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }
}
