<?php

namespace Trojan\Server\Network;

class Connection
{
    public Stream $stream;

    public function __construct(Stream $stream)
    {
        $this->stream = $stream;
    }
}