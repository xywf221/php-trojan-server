<?php

namespace Trojan\Server\Network;

use ArrayAccess;
use Stringable;

/**
 * @desc 一个模仿 C++ string的类
 */
class BString implements ArrayAccess, Stringable
{
    private $str;

    public function __construct($str)
    {
        $this->str = $str;
    }

    /**
     * @param string $needle
     * @return false|int
     */
    public function find(string $needle)
    {
        return strpos($this->str, $needle);
    }

    /**
     * @param int $offset
     * @param int|null $length
     * @return false|BString
     */
    public function substr(int $offset, ?int $length = null)
    {
        $flag = substr($this->str, $offset, $length);
        return is_bool($flag) ? false : new BString($flag);
    }

    public function length(): int
    {
        return strlen($this->str);
    }

    public function offsetExists($offset): bool
    {
        $length = strlen($this->str);
        return $offset >= 0 && $offset < $length;
    }

    public function offsetGet($offset)
    {
        $value = unpack('C', $this->str[$offset]);
        return is_bool($value) ? false : $value[1];
    }

    public function offsetSet($offset, $value)
    {
        $this->str[$offset] = pack("C", $value);
    }

    public function offsetUnset($offset)
    {
        // 用 0 填充
        $this->offsetSet($offset, 0);
    }

    public function __toString()
    {
        return $this->str;
    }
}