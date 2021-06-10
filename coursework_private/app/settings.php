<?php
/**
 * Settings.php
 *
 * File to configure all of slims default globals first stop if something has broken.
 *
 * @author Joe
 */

ini_set('display_errors', 'On');
ini_set('html_errors', 'On');
ini_set('xdebug.trace_output_name', 'stockquotes.%t');
ini_set('xdebug.trace_format', 1);

define('DIRSEP', DIRECTORY_SEPARATOR);

$url_root = $_SERVER['SCRIPT_NAME'];
$url_root = implode('/', explode('/', $url_root, -1));
$css_path = $url_root . '/css/standard.css';

$script_filename = $_SERVER["SCRIPT_FILENAME"];
$arr_script_filename = explode('/' , $script_filename, '-1');
$script_path = implode('/', $arr_script_filename) . '/';

define('CSS_PATH', $css_path);
define('APP_NAME', 'Coursework');
define('LANDING_PAGE', $url_root);

$wsdl = "https://m2mconnect.ee.co.uk/orange-soap/services/MessageServiceByCountry?wsdl";
define('WSDL', $wsdl);

define('M2MUSER', '20_2414379');
define('M2MPASSWORD', 'BCjUG2DqcEg@.@vK');

define ('BCRYPT_ALGO', PASSWORD_DEFAULT);
define ('BCRYPT_COST', 12);

$settings = [
  "settings" => [
    'displayErrorDetails' => true,
    'addContentLengthHeader' => false,
    'mode' => 'development',
    'debug' => true,
    'class_path' => __DIR__ . '/src/',
    'view' => [
      'template_path' => __DIR__ . '/templates/',
      'twig' => [
        'cache' => false,
        'auto_reload' => true,
      ]],
      'pdo_settings' => [
          'rdbms' => 'mysql',
          'host' => 'localhost',
          'db_name' => 'coursework_db',
          'port' => '3306',
          'user_name' => 'swad',
          'user_password' => 'password',
          'charset' => 'utf8',
          'collation' => 'utf8_unicode_ci',
          'options' => [
              PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
              PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
              PDO::ATTR_EMULATE_PREPARES   => true,
          ],
      ],
      'doctrine' => [
          // if true, metadata caching is forcefully disabled
          'dev_mode' => false,

          // path where the compiled metadata info will be cached
          // make sure the path exists and it is writable
          'cache_dir' => __DIR__ . '/var/doctrine',

          // you should add any other path containing annotated entity classes
          'metadata_dirs' => [__DIR__ . '/src/Domain'],

          'connection' => [
              'driver' => 'pdo_mysql',
              'host' => 'localhost',
              'port' => 3306,
              'dbname' => 'coursework_db',
              'user' => 'swad',
              'password' => 'password',
              'charset' => 'utf8mb4'
      ],
    ]
  ],
];

return $settings;
