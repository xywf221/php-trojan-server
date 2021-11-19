<?php

namespace Trojan\Server\Network\Protocol;

use Trojan\Server\Network\BString;

class Socks5Address
{
    public int $addressType;
    public int $port;
    public string $address;

    /**
     *
     * @param BString $data
     * @return array
     */
    function Parse(BString $data): array
    {
        if ($data->length() == 0 || ($data[0] != AddressType::IPv4 && $data[0] != AddressType::FQDN && $data[0] != AddressType::IPv6)) {
            return [false, 0];
        }

        $this->addressType = $data[0];
        switch ($this->addressType) {
            case AddressType::IPv4:
                if ($data->length() > 4 + 2) {
                    $this->address = sprintf("%d.%d.%d.%d", $data[1], $data[2], $data[3], $data[4]);
                    $this->port = $data[5] << 8 | $data[6];
                    return [true, 1 + 4 + 2];
                }
                break;
            case AddressType::FQDN:
                $domain_len = $data[1];
                if ($domain_len == 0) {
                    break;
                }
                if ($data->length() > 1 + $domain_len + 2) {
                    $this->address = $data->substr(2, $domain_len);
                    $this->port = $data[$domain_len + 2] << 8 | $data[$domain_len + 3];
                    return [true, 1 + 1 + $domain_len + 2];
                }
                break;
            case AddressType::IPv6:
                if ($data->length() > 16 + 2) {
                    $this->address = sprintf("%02x%02x:%02x%02x:%02x%02x:%02x%02x:%02x%02x:%02x%02x:%02x%02x:%02x",
                        $data[1], $data[2], $data[3], $data[4], $data[5], $data[7], $data[8], $data[9],
                        $data[10], $data[11], $data[12], $data[13], $data[14], $data[15], $data[16]);
                    $this->port = ($data[17] << 8) | $data[18];
                    return [true, 1 + 16 + 2];
                }
                break;
        }
        return [false, 0];
    }
}