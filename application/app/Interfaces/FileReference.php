<?php

declare(strict_types=1);

namespace App\Interfaces;

interface FileReference
{
    public const DISK = 's3';

    /**
     * Creates a human readable filename for this model
     * as the original filename is a random string.
     *
     * @return string
     */
    public function getReadableFilename(): string;

    /**
     * Creates an URL to access/download the file.
     *
     * @return string
     */
    public function getDownloadUrl(): string;
}
