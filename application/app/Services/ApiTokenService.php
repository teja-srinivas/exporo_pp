<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Contracts\Hashing\Hasher;

/**
 * Service that creates unique api tokens based on a service identifier
 * and customizable parameters for are unique for e.g. a request.
 */
class ApiTokenService
{
    /** @var Hasher */
    protected $hash;

    /**
     * Creates a new service instance.
     *
     * @param Hasher $hash
     */
    public function __construct(Hasher $hash)
    {
        $this->hash = $hash;
    }

    /**
     * Creates an api token for the given service with the given parameters.
     *
     * @param string $service
     * @param mixed ...$params
     * @return string
     */
    public function forService(string $service, ...$params): string
    {
        return base64_encode($this->hash->make($this->getIdentifier($service, $params)));
    }

    /**
     * Checks if the given "token" is valid for our service and parameter settings.
     *
     * @param string $service
     * @param string $token
     * @param mixed ...$params
     * @return bool
     */
    public function isValid(string $service, string $token, ...$params): bool
    {
        return $this->hash->check($this->getIdentifier($service, $params), base64_decode($token));
    }

    /**
     * @param string $service
     * @param array $params
     * @return string
     */
    protected function getIdentifier(string $service, array $params): string
    {
        return $service.'.'.implode('-', $params);
    }
}
