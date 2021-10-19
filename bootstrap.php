<?php


use Symfony\Component\Console\Application;
use Trojan\Server\Command\RunCommand;
use Trojan\Server\Version;

!defined('BASE_PATH') && define('BASE_PATH', __DIR__);

if (!file_exists(BASE_PATH . '/vendor/autoload.php')) {
    echo 'please execute `composer install` before run';
    exit(2);
}

require_once BASE_PATH . '/vendor/autoload.php';

$application = new Application();
$application->setVersion(Version::String());
$application->setName("trojan server");
$application->add(new RunCommand());
$application->run();
