<?php

namespace App;

use App\Traits\Dateable;
use App\Traits\HasRoles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class User extends Authenticatable implements AuditableContract
{
    use Notifiable;
    use HasRoles;
    use Auditable;
    use Dateable;

    /**
     * Possible user titles
     */
    const TITLES = [
        'Dr.',
        'Dr. med.',
        'Prof. Dr.',
        'Prof.',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $auditExclude = [
        'remember_token',
    ];


    /**
     * @return HasOne
     */
    public function details(): HasOne
    {
        return $this->hasOne(UserDetails::class, 'id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return BelongsToMany
     */
    public function agbs(): BelongsToMany
    {
        return $this->belongsToMany(Agb::class)->withTimestamps();
    }

    /**
     * @return HasMany
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function investors()
    {
        return $this->hasMany(Investor::class, 'last_user_id');
    }

    public function scopeOrdered(Builder $query)
    {
        $query->orderBy('last_name')->orderBy('first_name');
    }

    public function rejected()
    {
        return $this->hasRole(Role::PARTNER) && $this->rejected_at !== null;
    }

    public function accepted()
    {
        return $this->hasRole(Role::PARTNER) && $this->accepted_at !== null;
    }

    public function notYetAccepted()
    {
        return $this->hasRole(Role::PARTNER) && $this->accepted_at === null;
    }

    public static function getRoleColor(Role $role)
    {
        switch ($role->name ?? '') {
            case Role::ADMIN:
                return 'primary';
            case Role::INTERNAL:
                return 'success';
            default:
                return 'light';
        }
    }

    public static function getValidationRules(self $updating = null)
    {
        return [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users,email,' . optional($updating)->id,
        ];
    }
}
