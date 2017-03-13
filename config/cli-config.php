<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Zend\Mvc\Application;
use Zend\Stdlib\ArrayUtils;


// Setup/verify autoloading
if (file_exists($a = getcwd() . '/vendor/autoload.php')) {
    require $a;
} elseif (file_exists($a = __DIR__ . '/../../../autoload.php')) {
    require $a;
} elseif (file_exists($a = __DIR__ . '/../vendor/autoload.php')) {
    require $a;
} else {
    fwrite(STDERR, 'Cannot locate autoloader; please run "composer install"' . PHP_EOL);
    exit(1);
}

$appConfig = require __DIR__ . '/application.config.php';
if (file_exists(__DIR__ . '/development.config.php')) {
    $appConfig = ArrayUtils::merge($appConfig, require __DIR__ . '/development.config.php');
}
// init application
$application = Application::init($appConfig);
$serviceManager = $application->getServiceManager();
$entityManager = $serviceManager->get('doctrine.entitymanager.orm_default');

return ConsoleRunner::createHelperSet($entityManager);
