<?php
/**
 * Dependencies.php
 *
 * Place to register all containers on the slim app instance.
 */


// Register component on container
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;
use Slim\Container;


$container['view'] = function ($container) {
  $view = new \Slim\Views\Twig(
    $container['settings']['view']['template_path'],
    $container['settings']['view']['twig'],
    [
      'debug' => true // This line should enable debug mode
    ]
  );

  // Instantiate and add Slim specific extension
  $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
  $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

  // This line should allow the use of {{ dump() }} debugging in Twig
  $view->addExtension(new \Twig\Extension\DebugExtension());

  return $view;
};

$container['messageModel'] = function ($container) {
    $messageModel = new \Coursework\MessegeModel();
    return $messageModel;
};

$container['logger'] = function($container) {
    $logger = new Logger('name');
    $logger->pushHandler(new StreamHandler(__DIR__ . '/var/monolog.log', Logger::DEBUG));
    return $logger;
};

$container['userModel'] = function ($container) {
   $usermodel = new \Coursework\UserModel();
   return $usermodel;
};

$container['errorHandler'] = function ($container) {
    return(new Class {
        function forceExceptions() {
            return set_error_handler(function($errno, $errstr, $errfile, $errline ){
                throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
            });
        }

        function restorePrevious() {
            return restore_error_handler();
        }
    });
};


$container['databaseWrapper'] = function ($container) {
  $database_wrapper = new \Coursework\DatabaseWrapper();
  return $database_wrapper;
};

$container['sqlQueries'] = function ($container) {
  $sql_queries = new \Coursework\SQLQueries();
  return $sql_queries;
};

$container['processOutput'] = function ($container) {
    $retrieve_data_model = new \Coursework\ProcessOutput();
    return $retrieve_data_model;
};

$container['bcryptWrapper'] = function ($container) {
    $wrapper = new \Coursework\BcryptWrapper();
    return $wrapper;
};

$container['validator'] = function ($container)
{
    $validator = new \Coursework\Validator();
    return $validator;
};

$container['soapWrapper'] = function ($container) {
    $retrieve_stock_data_model = new \Coursework\SoapWrapper();
    return $retrieve_stock_data_model;
};


$container["messageRepository"] = function ($container) {
    return new \Coursework\MessageRepository($container[EntityManager::class]);
};

$container[EntityManager::class] = function (Container $container): EntityManager {
    $config = Setup::createAnnotationMetadataConfiguration(
        $container['settings']['doctrine']['metadata_dirs'],
        $container['settings']['doctrine']['dev_mode']
    );

    $config->setMetadataDriverImpl(
        new AnnotationDriver(
            new AnnotationReader,
            $container['settings']['doctrine']['metadata_dirs']
        )
    );

    $config->setMetadataCacheImpl(
        new FilesystemCache(
            $container['settings']['doctrine']['cache_dir']
        )
    );

    return EntityManager::create(
        $container['settings']['doctrine']['connection'],
        $config
    );
};

return $container;
