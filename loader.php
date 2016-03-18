<?php
/**
 *
 *
 * @author Sam Schmidt <samuel@dersam.net>
 * @since 2016-03-16
 */

use LinusShops\Prophet\Events;
use Behat\Behat\ApplicationFactory;
use Symfony\Component\Console\Input\ArgvInput;
use LinusShops\Prophet\Injector;

$frameworkPath = __DIR__;
$prophetRoot = $argv[1];
$modulePath = $argv[2];
$magentoPath = $argv[3];

//Local autoloader
require($frameworkPath.'/vendor/autoload.php');
require($prophetRoot.'/src/LinusShops/Prophet/Injector.php');

//Injector::bootMagento($magentoPath);
Injector::injectAutoloaders($modulePath, $magentoPath, $prophetRoot);

\LinusShops\Prophet\Injector::setPaths(array(
    'module' => $modulePath,
    'magento' => $magentoPath,
    'prophet' => $prophetRoot,
    'framework' => $frameworkPath
));

if (!is_file($modulePath.'/behat.yml')) {
    echo "behat.yml does not exist.".PHP_EOL;
    return;
}

$curdir = getcwd();
chdir($modulePath);

//Massage argv so that behat doesn't choke.
$args = $_SERVER['argv'];
if (isset($args[4])) {
    $args = array_slice($args, 4, null, true);
} else {
    $args = array();
}

//Find feature parameters and turn them into arguments
foreach ($args as $key => $arg) {
    $arg = trim($arg);
    //If it starts with features, turn it into an argument
    if (strpos($arg, 'features') === 0) {
        $fparam = explode('=', $arg);
        array_shift($fparam);
        unset($args[$key]);
        array_unshift($args, implode('=', $fparam));
    }
}

array_unshift($args, 'behat');

$input = new ArgvInput($args);

$factory = new ApplicationFactory();
$app = $factory->createApplication();
$app->setAutoExit(false);

Injector::dispatchPremodule();
$app->run($input);
Injector::dispatchPostmodule();
chdir($curdir);
