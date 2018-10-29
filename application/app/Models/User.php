<?php

namespace App\Models;

use App\Traits\Dateable;
use App\Traits\Encryptable;
use App\Traits\HasRoles;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

/**
 * @property int $id
 * @property int $parent_id
 * @property string $first_name
 * @property string $last_name
 * @property Carbon $accepted_at
 * @property Carbon $rejected_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property UserDetails $details
 * @property Collection $investors
 * @property Collection $investments
 * @property Collection $bonuses
 * @property Collection $bills
 * @property Company $company
 */
class User extends Authenticatable implements AuditableContract
{
    use Notifiable;
    use HasRoles;
    use Auditable;
    use Encryptable;
    use Dateable {
        asDateTime as parentAsDateTime;
    }

    /**
     * Possible user titles
     */
    const TITLES = [
        'Dr.',
        'Dr. med.',
        'Prof. Dr.',
        'Prof.',
    ];

    protected $encryptable = [
        'first_name',
        'last_name',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'first_name', 'last_name', 'email', 'password', 'api_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = [
        'accepted_at',
        'rejected_at',
    ];

    protected $auditExclude = [
        'remember_token',
    ];


    // Fix for the audit package not detecting this method (for some reason)
    protected function asDateTime($value)
    {
        return $this->parentAsDateTime($value);
    }

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
    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

    /**
     * @return HasMany
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function bonuses()
    {
        return $this->hasMany(CommissionBonus::class, 'user_id');
    }

    public function investors(): HasMany
    {
        return $this->hasMany(Investor::class, 'user_id');
    }

    public function investments()
    {
        return $this->hasManyThrough(Investment::class, Investor::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function canBeProcessed()
    {
        return $this->roles->isEmpty() || $this->hasRole(Role::PARTNER);
    }

    public function hasBeenProcessed()
    {
        return $this->canBeProcessed() && ($this->rejected_at !== null || $this->accepted_at !== null);
    }

    public function hasNotBeenProcessed()
    {
        return $this->canBeProcessed() && ($this->rejected_at === null && $this->accepted_at === null);
    }

    public function rejected()
    {
        return $this->canBeProcessed() && $this->rejected_at !== null;
    }

    public function accepted()
    {
        return $this->canBeProcessed() && $this->accepted_at !== null;
    }

    public function notYetAccepted()
    {
        return $this->canBeProcessed() && $this->accepted_at === null;
    }

    public function getDisplayName()
    {
        return trim("{$this->last_name}, {$this->first_name}", ', ');
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
}