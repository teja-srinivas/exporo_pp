<?php

namespace App\Models;

use App\Jobs\SendMail;
use App\Policies\BillPolicy;
use App\Traits\Encryptable;
use App\Traits\HasRoles;
use App\Traits\Person;
use Carbon\Carbon;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

/**
 * @property int $id
 * @property int $parent_id
 * @property string $salutation
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property Carbon $accepted_at
 * @property Carbon $rejected_at
 * @property Carbon $term_cancelled_at
 * @property Carbon $email_verified_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property UserDetails $details
 * @property Contract $contract
 * @property Contract $draftContract
 * @property Collection $investors
 * @property Collection $investments
 * @property Collection $bonuses
 * @property Collection $bills
 * @property Collection $documents
 * @property Collection $roles
 * @property Collection $agbs
 * @property Company $company
 */
class User extends Authenticatable implements AuditableContract, MustVerifyEmailContract
{
    use Notifiable;
    use HasRoles;
    use Auditable;
    use Encryptable;
    use MustVerifyEmail;
    use Person;

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
        'term_cancelled_at',
        'email_verified_at',
    ];

    protected $auditExclude = [
        'api_token',
        'remember_token',
    ];


    /**
     * @return HasOne
     */
    public function details(): HasOne
    {
        return $this->hasOne(UserDetails::class, 'id')->withDefault();
    }

    public function contract(): HasOne
    {
        return $this->hasOne(Contract::class)
            ->whereNotNull('accepted_at')
            ->whereNotNull('released_at')
            ->latest();
    }

    public function draftContract(): HasOne
    {
        return $this->hasOne(Contract::class)
            ->whereNull('accepted_at')
            ->latest();
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
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

    /**
     * Returns the commissions bonuses of the current contract.
     *
     * @return HasMany
     */
    public function bonuses(): HasMany
    {
        return $this->hasMany(CommissionBonus::class, 'contract_id', 'contract_id');
    }

    /**
     * Returns the id of the currently active contract.
     *
     * @return int
     */
    public function getContractIdAttribute()
    {
        return optional($this->contract)->getKey();
    }

    public function investors(): HasMany
    {
        return $this->hasMany(Investor::class, 'user_id');
    }

    public function investments(): HasManyThrough
    {
        return $this->hasManyThrough(Investment::class, Investor::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(User::class, 'parent_id', 'id');
    }

    public function canBeProcessed(): bool
    {
        return $this->hasRole(Role::PARTNER);
    }

    public function hasBeenProcessed(): bool
    {
        return $this->canBeProcessed() && ($this->rejected_at !== null || $this->accepted_at !== null);
    }

    public function hasNotBeenProcessed(): bool
    {
        return $this->canBeProcessed() && ($this->rejected_at === null && $this->accepted_at === null);
    }

    public function rejected(): bool
    {
        return $this->rejected_at !== null;
    }

    public function cancelled(): bool
    {
        return $this->term_cancelled_at !== null;
    }

    public function accepted(): bool
    {
        return $this->accepted_at !== null;
    }

    public function notYetAccepted(): bool
    {
        return $this->accepted_at === null;
    }

    /**
     * Builds and returns a usable greeting as you would see on letters.
     *
     * @return string
     */
    public function getGreeting(): string
    {
        $salutation = optional($this->details)->salutation;
        $greeting = [];

        if ($salutation === 'male') {
            $greeting[] = 'Sehr geehrter Herr';
        } elseif ($salutation === 'female') {
            $greeting[] = 'Sehr geehrte Frau';
        } else {
            $greeting[] = 'Sehr geehrte Damen und Herren';
        }

        $title = optional($this->details)->title;

        if ($title !== null) {
            $greeting[] = $title;
        }

        $greeting[] = trim($this->last_name);

        return implode(' ', $greeting);
    }

    public function getLoginLink(): string
    {
        return URL::signedRoute('users.login', [$this]);
    }

    public function sendEmailVerificationNotification(): void
    {
        SendMail::dispatch([
            'Activationhash' => URL::signedRoute('verification.verify', [$this->id]),
        ], $this, config('mail.templateIds.registration'))->onQueue('emails');
    }

    public function canBeBilled(): bool
    {
        return $this->can(BillPolicy::CAN_BE_BILLED_PERMISSION);
    }

    public function canBeAccepted(): bool
    {
        return $this->email_verified_at !== null;
    }

    /**
     * @return bool
     */
    public function hasActiveContract(): bool
    {
        return $this->contract !== null;
    }

    public function getDisplayName(): string
    {
        return $this->details->display_name;
    }
}
