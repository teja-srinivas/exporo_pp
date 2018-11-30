<?php

namespace App\Events;

use App\Models\Schema;
use Illuminate\Queue\SerializesModels;

class SchemaUpdated
{
    use SerializesModels;

    /**
     * @var Schema
     */
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
