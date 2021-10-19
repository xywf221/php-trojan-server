<?php

namespace Trojan\Server\Command;

use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Trojan\Server\Network\TLSCustomServer;
use function Co\run;

class RunCommand extends Command
{
    protected function configure()
    {
        $this->setName('run')
            ->setDescription('Run TrojanRequest Server')
            ->setHelp("run config.yaml")
            ->addOption("format", 'f', InputOption::VALUE_OPTIONAL, 'config file format', 'YAML')
            ->addArgument("file", InputArgument::REQUIRED, 'config file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $file = $input->getArgument('file');
        $format = $input->getOption('format');
        if (!file_exists($file)) {
            throw new RuntimeException('config file not exists');
        }

        switch (strtoupper($format)) {
            case 'YAML':
                $config = yaml_parse_file($file);
                break;
            case 'JSON':
                $config = json_decode(file_get_contents($file), true);
                break;
            default:
                throw new RuntimeException("unsupported file format:$format");
        }
        //拿到数据初始化Server
        $server = new TLSCustomServer();
        $server->setBindAddress($config['bind_address']);
        $server->setBindPort($config['bind_port']);

        $server->setRemoteAddress($config['remote_address']);
        $server->setRemotePort($config['remote_port']);


        $server->setSni($config['sni']);
        $server->setCert($config['cert']);
        $server->setPk($config['pk']);

        run(function () use ($server) {
            $server->start();
        });
        return Command::SUCCESS;
    }
}