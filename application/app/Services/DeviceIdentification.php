<?php

namespace App\Services;

use Jenssegers\Agent\Agent;

class DeviceIdentification
{
    const PHONE = 'phone';
    const TABLET = 'tablet';
    const DESKTOP = 'desktop';

    const DEVICES = [
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
