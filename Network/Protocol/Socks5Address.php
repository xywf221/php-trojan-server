<?php

namespace Trojan\Server\Network\Protocol;

class Socks5Address
{
    public int $addressType;
    public int $port;
    public string $address;

    /**
     *
     * @param $data
     * @param $addressLen
     * @return bool
     */
    function Parse($data, &$addressLen): bool
    {
        if (strlen($data) == 0) {
            return false;
        }
        $this->addressType = ord($data[0]);
        switch ($this->addressType) {
            case AddressType::IPv4:
                if (strlen($data) > 4 + 2) {
                    $this->address = sprintf("%d.%d.%d.%d", ord($data[1]), ord($data[2]), ord($data[3]), ord($data[4]));
                    $this->port = ord($data[5]) << 8 | ord($data[6]);
                    $addressLen = 1 + 4 + 2;
                    return true;
                }
                break;
            case AddressType::FQDN:
                $domainLen = ord($data[1]);
                if (empty($domainLen)) {
                    break;
                }
                if (strlen($data) > (1 + $domainLen + 2)) {
                    $this->address = substr($data, 2, $domainLen);
                    $this->port = ord($data[$domainLen + 2]) << 8 | ord($data[$domainLen + 3]);
                    $addressLen = 1 + 1 + $domainLen + 2;
                    return true;
                }
                break;
            case AddressType::IPv6:
                if (strlen($data) > 16 + 2) {
                    $this->address = sprintf("%02x%02x:%02x%02x:%02x%02x:%02x%02x:%02x%02x:%02x%02x:%02x%02x:%02x%02x",
                        ord($data[1]), ord($data[2]), ord($data[3]), ord($data[4]), ord($data[5]), ord($data[7]), ord($data[8]), ord($data[9]),
                        ord($data[10]), ord($data[11]), ord($data[12]), ord($data[13]), ord($data[14]), ord($data[15]), ord($data[16]));
                    $this->port = (ord($data[17]) << 8) | ord($data[18]);
                    $addressLen = 1 + 16 + 2;
                    return true;
                }
                break;
            default:
                return false;
        }
        return false;
    }
}