<?php

namespace Trojan\Server\Network;

use RuntimeException;

class StreamServer
{
    /**
     * @var $socket resource
     */
    private $socket;

    public function listen(string $address, array $options = [])
    {
        $socket = stream_socket_server($address, $errorCode, $errorMessage, STREAM_SERVER_BIND | STREAM_SERVER_LISTEN, $this->createContext($options));
        if ($errorCode != 0) {
            throw new RuntimeException($errorMessage, $errorCode);
        }
        $this->socket = $socket;
    }

    public function accept($timeout = -1)
    {
        $handle = stream_socket_accept($this->socket, $timeout);
        return is_resource($handle) ? new Connection(new Stream($handle)) : false;
    }

    private function createContext($options)
    {
        return stream_context_create($options);
    }

    public function close(): bool
    {
        return stream_socket_shutdown($this->socket, STREAM_SHUT_RDWR);
    }
}