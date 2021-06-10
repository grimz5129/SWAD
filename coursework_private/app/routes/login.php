<?php
// initialize the session

/**
 * login.php
 *
 * This page will let a user register while validating and sanitising data.
 * The user password is also hashed before it is inserted into the database
 *
 * @author Yefri & Nicholas
 */

use \PSr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/login', function(Request $request, Response $response) use ($app) {
// header('location: /messegeView')
//define variables and initialize with empty vales
  $app->getContainer()->get('logger')->info('New login request');
  $username = $password = "";
  $username_err = $password_err = "";
  $error_message = '';
  $_SESSION['LoggedIn'] = false;
     // Check if username is empty backend
    if(empty($_POST["username"]) || empty($_POST['password'])){
      $app->getContainer()->get('logger')->notice('One or more login fields are empty');
      $error_message = 'Fields cannot be empty.';
      return $this->view->render($response,
          'homepage.html.twig',
          [
              'landing_page' => $_SERVER["SCRIPT_NAME"],
              'error2' => $error_message,
          ]
      );
    } else {
        $password = trim($_POST["password"]);
        $username = trim($_POST["username"]);
        $app->getContainer()->get('logger')->info('Validation passed, executing login for: ' . $username);

        $userDetails = getUserDetails($app, $username);

        if ($userDetails === null || is_bool($userDetails)) {
            $_SESSION['LoggedIn'] = false;
            $error_message = 'Password or username is incorrect.';
            $app->getContainer()->get('logger')->notice('NULL USER DATA RETURNED FROM DB for user: ' . $username);
            return $this->view->render($response,
                'homepage.html.twig',
                [
                    'landing_page' => $_SERVER["SCRIPT_NAME"],
                    'error2' => $error_message,
                ]
            );
        }
        elseif (password_verify($password, $userDetails[2]) === true) {
            $app->getContainer()->get('logger')->debug('Password correct for user: ' . $username);
           $_SESSION['LoggedIn'] = true;
           header('Location: messegeView');
           die();
           /*return $this->view->render($response,
               'messegeView.html.twig',
               [
                 header('location: /messegeView')
               ]
           );*/
        } else {
          $_SESSION['LoggedIn'] = false;
          $error_message = 'Password or username is incorrect.';
          $app->getContainer()->get('logger')->notice($error_message . ' for user: ' . $username);
          return $this->view->render($response,
              'homepage.html.twig',
              [
                  'landing_page' => $_SERVER["SCRIPT_NAME"],
                  'error2' => $error_message,
              ]
          );
        }
    }
});
/**
 * stores the user in database
 *
 * @param $app
 * @param $username
 */
function getUserDetails($app, $username) {
  $app->getContainer()->get('logger')->debug('In function getUserDetails for user ' . $username);
  $storage_result = [];
  $store_result = '';
  $settings = $app->getContainer()->get('settings');
  $database_connection_settings = $settings['pdo_settings'];
  $doctrine_queries = $app->getContainer()->get('sqlQueries');
  $database_connection = $app->getContainer()->get('databaseWrapper');
  $userModel = $app->getContainer()->get('userModel');

  $retrievedUserData = null;

  $app->getContainer()->get('errorHandler')->forceExceptions();
  try{
      $userModel->setDatabaseConnectionSettings($database_connection_settings);
      $userModel->setDatabaseWrapper($database_connection);
      $userModel->setSqlQueries($doctrine_queries);
      $retrievedUserData = $userModel->retrieveUserData($username);
  }
  catch (Exception $e) {
      $app->getContainer()->get('logger')->warning($e->getMessage());
      $app->getContainer()->get('logger')->warning($e);
      die('Whooops, something went wrong when trying to log in!');
  }
  $app->getContainer()->get('errorHandler')->restorePrevious();

  return $retrievedUserData;
}
