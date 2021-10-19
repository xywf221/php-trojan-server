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
                $data = yaml_parse_file($file);
                break;
            case 'JSON':
                $data = json_decode(file_get_contents($file), true);
                break;
            default:
                throw new RuntimeException("unsupported file format:$format");
        }
        //拿到数据初始化Server
        $server = new TLSCustomServer();
        $server->setBindAddress('127.0.0.1');
        $server->setBindPort(4433);

        $server->setRemoteAddress('127.0.0.1');
        $server->setRemotePort(8000);


        $server->setSni('neo-term-repo.proce.top');
        $server->setCert('/Users/dmls/Desktop/trojan-server/testdata/neo-term-repo.proce.top.crt');
        $server->setPk('/Users/dmls/Desktop/trojan-server/testdata/neo-term-repo.proce.top.key');

        run(function () use ($server) {
            $server->start();
        });
        return Command::SUCCESS;
    }
}