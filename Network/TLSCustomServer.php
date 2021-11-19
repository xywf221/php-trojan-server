<?php

namespace Trojan\Server\Network;

use co;
use RuntimeException;
use Trojan\Server\Network\Protocol\TrojanRequest;
use function Co\run;

class TLSCustomServer
{


    # 服务器证书相关配置
    private string $cert;
    private string $pk;
    private string $passphrase = '';
    private string $sni = '';

    # 地址相关的配置
    private string $bindAddress;
    private int $bindPort;

    private string $remoteAddress = '';
    private int $remotePort = 0;

    /**
     * @return string
     */
    public function getBindAddress(): string
    {
        return $this->bindAddress;
    }

    /**
     * @param string $bindAddress
     */
    public function setBindAddress(string $bindAddress): void
    {
        $this->bindAddress = $bindAddress;
    }

    /**
     * @return int
     */
    public function getBindPort(): int
    {
        return $this->bindPort;
    }

    /**
     * @param int $bindPort
     */
    public function setBindPort(int $bindPort): void
    {
        $this->bindPort = $bindPort;
    }


    /**
     * @return string
     */
    public function getCert(): string
    {
        return $this->cert;
    }

    /**
     * @param string $cert
     */
    public function setCert(string $cert): void
    {
        $this->cert = $cert;
    }

    /**
     * @return string
     */
    public function getPk(): string
    {
        return $this->pk;
    }

    /**
     * @param string $pk
     */
    public function setPk(string $pk): void
    {
        $this->pk = $pk;
    }

    /**
     * @return string
     */
    public function getPassphrase(): string
    {
        return $this->passphrase;
    }

    /**
     * @param string $passphrase
     */
    public function setPassphrase(string $passphrase): void
    {
        $this->passphrase = $passphrase;
    }

    /**
     * @return string
     */
    public function getSni(): string
    {
        return $this->sni;
    }

    /**
     * @param string $sni
     */
    public function setSni(string $sni): void
    {
        $this->sni = $sni;
    }


    /**
     * @return string
     */
    public function getRemoteAddress(): string
    {
        return $this->remoteAddress;
    }

    /**
     * @param string $remoteAddress
     */
    public function setRemoteAddress(string $remoteAddress): void
    {
        $this->remoteAddress = $remoteAddress;
    }

    /**
     * @return int
     */
    public function getRemotePort(): int
    {
        return $this->remotePort;
    }

    /**
     * @param int $remotePort
     */
    public function setRemotePort(int $remotePort): void
    {
        $this->remotePort = $remotePort;
    }


    private function getOptions(): array
    {
        return [
            'ssl' => [
                'SNI_enabled' => true,
                'peer_name' => $this->getSni(),
                'local_cert' => $this->getCert(),
                'local_pk' => $this->getPk(),
                'passphrase' => $this->getPassphrase()
            ]
        ];
    }

    /**
     * @desc 启动服务
     */
    public function start()
    {
        $server = new StreamServer();
        $server->listen(sprintf("tls://%s:%d", $this->getBindAddress(), $this->getBindPort()), $this->getOptions());
        while (true) {
            $conn = $server->accept();
            if (!$conn) {
                continue;
            }
            go(function () use ($conn) {
                $this->handleConn($conn);
            });
        }
    }

    /**
     * @desc 处理请求
     * @param Connection $conn
     */
    public function handleConn(Connection $conn)
    {
        $buff = $conn->stream->read(8192);
        $req = new TrojanRequest();
        $valid = $req->Parse($buff);

        $queryAddress = $valid ? $req->socks5Address->address : $this->getRemoteAddress();
        $queryPort = $valid ? $req->socks5Address->port : $this->getRemotePort();
        $conn->stream->write($req->payload);


//
//        $clientConn = stream_socket_client(sprintf('tcp://%s:%d', $queryAddress, $queryPort));
//        // 貌似是一个线程的问题 Co::join 会有一个协程卡死(不会主动退出) 解决方案是 增加超时时间 或者 手动取消协程
//        $direction_remote_cid = $direction_client_cid = 0;
//        stream_set_timeout($tlsConn->resource, 12);
//        stream_set_timeout($clientConn, 12);
//        //把已经读到的数据写入
//        fwrite($clientConn, $valid ? $req->payload : $data);
//        // client < == > remote
//        Co::join([
//            go(function () use ($clientConn, $tlsConn, &$direction_remote_cid, $direction_client_cid) {
//                $direction_remote_cid = co::getCid();
//                stream_copy_to_stream($clientConn, $tlsConn->resource);
//                co::cancel($direction_client_cid);
//            }),
//            go(function () use ($clientConn, $tlsConn, &$direction_client_cid, $direction_remote_cid) {
//                $direction_client_cid = co::getCid();
//                stream_copy_to_stream($tlsConn->resource, $clientConn);
//                co::cancel($direction_remote_cid);
//            })
//        ]);
//        var_dump(memory_get_usage());
    }


}