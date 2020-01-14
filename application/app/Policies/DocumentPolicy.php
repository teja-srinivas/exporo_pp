<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Document;

class DocumentPolicy extends BasePolicy
{
    public const PERMISSION = 'management.documents';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }

    public function contracts(User $user, Document $document)
    {
        return $user->can('management.documents.view-contracts');
    }
}
