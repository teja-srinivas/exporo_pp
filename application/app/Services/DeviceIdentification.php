<?php

declare(strict_types=1);

namespace App\Services;

use Jenssegers\Agent\Agent;

class DeviceIdentification
{
    public const PHONE = 'phone';

    public const TABLET = 'tablet';

    public const DESKTOP = 'desktop';

    public const DEVICES = [
        self::PHONE,
        self::TABLET,
        self::DESKTOP,
    ];

    /**
     * Tries to identify the current device family.
     * If the device type is not supported, this will return null (e.g. robots).
     *
     * @return string|null
     */
    public static function identify(): ?string
    {
        $agent = new Agent();

        if ($agent->isTablet()) {
            return self::TABLET;
        }

        if ($agent->isDesktop()) {
            return self::DESKTOP;
        }

        if ($agent->isMobile()) {
            return self::PHONE;
        }

        return null;
    }
}
