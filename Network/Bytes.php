<?php

namespace Trojan\Server\Network;


class Bytes
{
    //或者根据string做一个类

    static function formBinary($binaryData, string $format = 'C*')
    {
        if (is_bool($binaryData)) {
            return [];
        }
        return unpack($format, $binaryData);
    }

    static function toBinary(array $bytes, string $format = 'C*')
    {
        return pack($format, ...$bytes);
    }
}


