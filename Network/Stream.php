<?php

namespace Trojan\Server\Network;

class Stream
{
    /**
     * @var $handle resource
     */
    private $handle;

    /**
     * @param $handle resource
     */
    public function __construct($handle)
    {
        $this->handle = $handle;
    }

    public function close(): bool
    {
        return fclose($this->handle);
    }

    /**
     * @param $length
     * @return BString|bool
     */
    public function read($length)
    {
        $data = fread($this->handle, $length);
        return is_bool($data) ? false : new BString($data);
    }

    public function write(string $buffer)
    {
        return fwrite($this->handle, $buffer);
    }
}