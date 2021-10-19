<?php

namespace Trojan\Server;

class Version
{
    const MAJOR = 1;
    const MINOR = 0;
    const PATCH = 0;

    public static function String(): string
    {
        return sprintf("%d.%d.%d", self::MAJOR, self::MINOR, self::PATCH);
    }
}