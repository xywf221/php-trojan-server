<?php


use Symfony\Component\Console\Application;
use Trojan\Server\Command\RunCommand;
use Trojan\Server\Version;

!defined('BASE_PATH') && define('BASE_PATH', __DIR__);

require_once BASE_PATH . '/vendor/autoload.php';

$application = new Application();
$application->setVersion(Version::String());
$application->setName("trojan server");
$application->add(new RunCommand());
$application->run();
