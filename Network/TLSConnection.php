<?php

namespace Trojan\Server\Network;

use RuntimeException;

class TLSConnection
{
    public $resource;

    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    public function read($length = 512)
    {
        return fread($this->resource, $length);
    }

    public function write($data)
    {
        return fwrite($this->resource, $data);
    }

    /**
     * 从tcp升级到tls
     * @param $resource
     * @return TLSConnection
     */
    public static function upgrade($resource): self
    {
        $flag = stream_socket_enable_crypto($resource, true, STREAM_CRYPTO_METHOD_TLSv1_2_SERVER);
        if ($flag) {
            return new self($resource);
        } else {
            throw new RuntimeException('upgrade failed');
        }
    }
}