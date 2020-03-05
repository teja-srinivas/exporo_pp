<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use App\LinkClick;
use App\LinkInstance;
use App\Jobs\SendMail;
use App\Traits\Person;
use App\Traits\HasRoles;
use App\Events\UserUpdated;
use App\Traits\Encryptable;
use App\Policies\BillPolicy;
use App\Builders\UserBuilder;
use OwenIt\Auditing\Auditable;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;

/**
 * @method static UserBuilder query()
 *
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
 * @property PartnerContract $contract
 * @property PartnerContract $partnerContract
 * @property ProductContract $productContract
 * @property Collection $contracts
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
    use SoftDeletes;

    /**
     * Possible user titles.
     */
    public const TITLES = [
        'Dr.',
        'Dr. med.',
        'Prof. Dr.',
        'Prof.',
    ];

    protected $dispatchesEvents = [
        'updated' => UserUpdated::class,
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
        'id', 'first_name', 'last_name', 'email', 'password', 'api_token', 'accepted_at', 'rejected_at',
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
        'deleted_at',
    ];

    protected $auditExclude = [
        'api_token',
        'remember_token',
    ];

    /**
     * @return HasOne
     */
    public function embed(): HasOne
    {
        return $this->hasOne(Embed::class);
    }

    /**
     * @return HasOne
     */
    public function details(): HasOne
    {
        return $this->hasOne(UserDetails::class, 'id')->withDefault();
    }

    public function contract(): HasOne
    {
        return $this->partnerContract();
    }

    public function partnerContract(): HasOne
    {
        return $this->hasOne(PartnerContract::class)
            ->whereNotNull('accepted_at')
            ->whereNotNull('released_at')
            ->whereNull('terminated_at')
            ->latest();
    }

    public function releasedPartnerContract(): HasOne
    {
        return $this->hasOne(PartnerContract::class)
            ->whereNotNull('released_at')
            ->latest();
    }

    public function partnerContracts(): HasMany
    {
        return $this->hasMany(PartnerContract::class);
    }

    public function linkClicks(): HasManyThrough
    {
        return $this->hasManyThrough(
            LinkClick::class,
            LinkInstance::class,
            'user_id',
            'instance_id',
            'id',
            'id'
        );
    }

    public function productContract(): HasOne
    {
        return $this->hasOne(ProductContract::class)
            ->whereNotNull('accepted_at')
            ->whereNotNull('released_at')
            ->whereNull('terminated_at')
            ->latest();
    }

    public function releasedProductContract(): HasOne
    {
        return $this->hasOne(ProductContract::class)
            ->whereNotNull('released_at')
            ->latest();
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class);
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
    
    public function activeAgbByType($type)
    {
        return $this->agbs
            ->where('type', $type)
            ->where('effective_from', '<=', now())
            ->where('is_default', 1);
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
        return $this->hasMany(CommissionBonus::class, 'contract_id', 'product_contract_id');
    }

    /**
    * Returns bonuses matching parent's bonuses.
    *
    * @param User $user
    * @return Collection
    */
    public function bonusesMatchingParent(self $user)
    {
        $parentBonuses = $user->bonuses()
            ->join('contracts', 'contracts.id', 'commission_bonuses.contract_id')
            ->whereNotNull('contracts.accepted_at')
            ->whereNotNull('contracts.released_at')
            ->whereNull('contracts.terminated_at')
            ->where('commission_bonuses.is_overhead', true)
            ->get();
        $query = $this->bonuses();

        $query->where(static function (Builder $query) use ($parentBonuses) {
            foreach ($parentBonuses as $parentBonus) {
                $query->orWhere(static function (Builder $query) use ($parentBonus) {
                    $query->where('type_id', $parentBonus->type_id);
                    $query->where('calculation_type', $parentBonus->calculation_type);
                });
            }
        });

        return $query->get();
    }

    /**
     * Returns the id of the currently active contract.
     *
     * @return int
     */
    public function getProductContractIdAttribute()
    {
        return optional($this->productContract)->getKey();
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
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function canBeProcessed(): bool
    {
        return $this->hasRole(Role::PARTNER);
    }

    public function hasNotBeenProcessed(): bool
    {
        return $this->rejected_at === null && $this->accepted_at === null;
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
        return $this->canBeProcessed() && ($this->accepted_at === null || ! $this->hasReleasedContract());
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
        $url = URL::temporarySignedRoute(
            'verification.verify',
            \Illuminate\Support\Carbon::now()->addMinutes((int) config('auth.verification.expire', 60 * 24 * 2)),
            [
                'id' => $this->getKey(),
                'hash' => sha1($this->getEmailForVerification()),
            ]
        );

        SendMail::dispatch(['Activationhash' => $url], $this, 'registration')->onQueue('emails');
    }

    public function canBeBilled(): bool
    {
        return $this->can(BillPolicy::CAN_BE_BILLED_PERMISSION);
    }

    public function isDeleted(): bool
    {
        return $this->deleted_at !== null;
    }

    public function canBeAccepted(): bool
    {
        if (!$this->hasVerifiedEmail()) {
            return false;
        }

        if ($this->releasedPartnerContract === null) {
            return false;
        }

        return $this->releasedProductContract !== null;
    }

    public function hasActiveContract(): bool
    {
        return $this->partnerContract !== null;
    }

    public function hasReleasedContract(): bool
    {
        return $this->releasedPartnerContract !== null;
    }

    public function getDisplayName(): string
    {
        $name = trim($this->details->display_name);

        return $name ?: $this->getAnonymousName();
    }

    public function sendPasswordResetNotification($token)
    {
        SendMail::dispatch([
            'user-email' => $this->email,
            'link' => url('password/reset/'.$token),
        ], $this, 'resetPassword')->onQueue('emails');
    }

    public function getRateLimitAttribute()
    {
        return $this->hasPermissionTo('features.api.no-limit') ? 99999999 : 60;
    }

    public function newEloquentBuilder($query): UserBuilder
    {
        return new UserBuilder($query);
    }
}
