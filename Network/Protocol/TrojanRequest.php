<?php

namespace Trojan\Server\Network\Protocol;

use Trojan\Server\Network\BString;

class TrojanRequest
{
    const CONNECT = 1;
    const UDP_ASSOCIATE = 3;

    public int $command;

    public Socks5Address $socks5Address;

    public BString $payload;

    public string $password;


    function Parse(BString $data): bool
    {
        $first = $data->find("\r\n");
        if ($first === false) {
            return false;
        }
        $this->password = $data->substr(0, $first);
        $this->payload = $data->substr($first + 2);

        if ($this->payload->length() == 0 || ($this->payload[0] !== self::CONNECT && $this->payload[0] != self::UDP_ASSOCIATE)) {
            return false;
        }
        $this->command = $this->payload[0];
        $this->socks5Address = new Socks5Address();
        list($is_address_valid, $address_len) = $this->socks5Address->Parse($this->payload->substr(1));
        if (!$is_address_valid || $this->payload->length() < $address_len + 3 || $this->payload->substr($address_len + 1, 2) != "\r\n") {
            return false;
        }
        $this->payload = $this->payload->substr($address_len + 3);
        return true;
    }
}