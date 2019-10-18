<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Schema;
use Illuminate\Queue\SerializesModels;

class SchemaUpdated
{
    use SerializesModels;

    /** @var Schema */
    public $schema;

    /**
     * Create a new event instance.
     *
     * @param Schema $schema
     */
    public function __construct(Schema $schema)
    {
        $this->schema = $schema;
    }
}
