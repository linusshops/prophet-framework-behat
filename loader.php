<?php
/**
 *
 *
 * @author Sam Schmidt <samuel@dersam.net>
 * @since 2016-03-16
 */

use LinusShops\Prophet\Events;
use LinusShops\Prophet\Magento;
use Behat\Behat\ApplicationFactory;
use Symfony\Component\Console\Input\ArgvInput;

$frameworkPath = __DIR__;
$prophetRoot = $argv[1];
$modulePath = $argv[2];
$magentoPath = $argv[3];

//Local autoloader
require($frameworkPath.'/vendor/autoload.php');

//Prophet autoloader
require($prophetRoot.'/vendor/autoload.php');

$options = new \LinusShops\Prophet\Events\Options();

Events::dispatch(Events::PROPHET_PREMAGENTO, $options);
Magento::bootstrap($options);
Events::dispatch(Events::PROPHET_POSTMAGENTO);

Magento::injectAutoloaders($modulePath, $magentoPath);

$curdir = getcwd();
chdir($modulePath);

//Massage argv so that behat doesn't choke.
$args = $_SERVER['argv'];
if (isset($args[4])) {
    $args = array_slice($args, 4, null, true);
} else {
    $args = array();
}

array_unshift($args, 'behat');

$input = new ArgvInput($args);

$factory = new ApplicationFactory();
$app = $factory->createApplication();
$app->setAutoExit(false);

$app->run($input);
chdir($curdir);
