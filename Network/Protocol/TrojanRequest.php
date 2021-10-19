<?php

namespace Trojan\Server\Network\Protocol;

use Trojan\Server\Network\TLSConnection;

class TrojanRequest
{
    const CONNECT = 1;

    public int $command;

    public Socks5Address $socks5Address;

    public string $payload;

    public string $password;


    function Parse($data): bool
    {
        $first = strpos($data, "\r\n");
        if ($first === false) {
            return false;
        }
        $this->password = substr($data, 0, $first);
        if (strlen($this->password) != 56) {
            return false;
        }
        $this->payload = substr($data, $first + 2);

        //暂时不打算支持udp
        if (strlen($this->payload) == 0 || ord($this->payload[0]) != self::CONNECT) {
            return false;
        }

        $this->command = ord($this->payload[0]);
        $this->socks5Address = new Socks5Address();
        $addressLen = 0;
        $isAddressValid = $this->socks5Address->Parse(substr($this->payload, 1), $addressLen);

        if (!$isAddressValid || strlen($this->payload) < $addressLen + 3 || substr($this->payload, $addressLen + 1, 2) != "\r\n") {
            return false;
        }

        $this->payload = substr($this->payload, $addressLen + 3);
        return true;
    }
}