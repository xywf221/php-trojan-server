## 项目说明

使用 PHP + Swoole 实现的trojan的简单服务器只支持核心功能(TLS)

运行环境 : `PHP >= 7.4` & `Swoole >= 4.8.0`

运行 : `php ./bootstrap.php run config.yaml` 需要更改里面的配置参数

## 代做

处理协议这块可以考虑使用 unpack("C*", $payload) 来做 bytes 数据就是一组u8数据