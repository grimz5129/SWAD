<?php

/**
 * register.php
 *
 * This page will let a user register while validating and sanitising data.
 * The user password is also hashed before it is inserted into the database
 *
 * @author Yefri
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/register', function(Request $request, Response $response) use ($app) {
    $app->getContainer()->get('logger')->debug('Starting registration for new user');
  //creating an error message variable
    $error_message = '';
    //validates the register form
if ($_POST['password'] != $_POST['password-verify']) {
    $error_message .= ' Passwords do not match.';
    $app->getContainer()->get('logger')->notice($error_message);
    return $this->view->render($response,
        'homepage.html.twig',
        [
            'landing_page' => $_SERVER["SCRIPT_NAME"],
            'error' => $error_message,
        ]
    );
} if(strlen($_POST['password']) < 8){
    $error_message .= ' Passwords needs to be at least 8 characters long.';
    $app->getContainer()->get('logger')->notice($error_message);
    return $this->view->render($response,
        'homepage.html.twig',
        [
            'landing_page' => $_SERVER["SCRIPT_NAME"],
            'error' => $error_message,
        ]
    );
} if(strlen($_POST['username']) < 6){
    $error_message .= ' Username needs to be at least 8 characters long.';
    $app->getContainer()->get('logger')->notice($error_message);
    return $this->view->render($response,
        'homepage.html.twig',
        [
            'landing_page' => $_SERVER["SCRIPT_NAME"],
            'error' => $error_message,
        ]
    );
} if (!preg_match("#[A-Z]+#", $_POST['password'])){
    $error_message .= ' Password must contain at least 1 uppercase letter.';
    $app->getContainer()->get('logger')->notice($error_message);
        return $this->view->render($response,
            'homepage.html.twig',
            [
                'landing_page' => $_SERVER["SCRIPT_NAME"],
                'error' => $error_message,
            ]
        );
} if (!preg_match("#[a-z]+#", $_POST['password'])){
    $error_message .= ' Password must contain at least 1 lowercase letter.';
    $app->getContainer()->get('logger')->notice($error_message);
        return $this->view->render($response,
            'homepage.html.twig',
            [
                'landing_page' => $_SERVER["SCRIPT_NAME"],
                'error' => $error_message,
            ]
        );
} if(!preg_match("#[0-9]+#", $_POST['password'])) {
    $error_message .= 'Password must contain at least 1 number.';
    $app->getContainer()->get('logger')->notice($error_message);
    return $this->view->render($response,
        'homepage.html.twig',
        [
            'landing_page' => $_SERVER["SCRIPT_NAME"],
            'error' => $error_message,
        ]
    );
} else
    {
        $app->getContainer()->get('logger')->info('All validation passed');
    $tainted_parameters = $request->getParsedBody();

    $cleaned_parameters = cleanupParameters($app, $tainted_parameters);
    $hashed_password = hash_password($app, $cleaned_parameters['password']);

    $storage_result = storeUserDetails($app, $cleaned_parameters, $hashed_password);

    $html_output = $this->view->render($response,
        'register.html.twig',
        [
            'landing_page' => $_SERVER["SCRIPT_NAME"],
            'css_path' => CSS_PATH,
            'page_title' => APP_NAME,
            'page_heading_2' => 'Result',
            'text' => 'User has been successfully registered',
        ]);

    processOutput($app, $html_output);

    $app->getContainer()->get('logger')->info('User registration complete');

    return $html_output;
}
});

/**
 * Wrapper for the validator to sanitise the username and email
 *
 * @param $app
 * @param $tainted_parameters
 * @return array
 */
function cleanupParameters($app, $tainted_parameters)
{
    $cleaned_parameters = [];
    $validator = $app->getContainer()->get('validator');

    $tainted_username = $tainted_parameters['username'];
    $tainted_email = $tainted_parameters['email'];

    $cleaned_parameters['password'] = $tainted_parameters['password'];
    $cleaned_parameters['sanitised_username'] = $validator->sanitiseString($tainted_username);
    $cleaned_parameters['sanitised_email'] = $validator->sanitiseEmail($tainted_email);
    return $cleaned_parameters;
}

/**
 * Uses the Bcrypt library to create hashes of the entered password
 *
 * @param $app
 * @param $password_to_hash
 * @return string
 */
function hash_password($app, $password_to_hash): string
{
    $app->getContainer()->get('logger')->debug('Hashing user password');
    $bcrypt_wrapper = $app->getContainer()->get('bcryptWrapper');
    $hashed_password = $bcrypt_wrapper->createHashedPassword($password_to_hash);
    return $hashed_password;
}

/**
 * stores the user in database
 *
 * @param $app
 * @param array $cleaned_parameters
 * @param string $hashed_password
 */
function storeUserDetails($app, array $cleaned_parameters, string $hashed_password): string
{
    $app->getContainer()->get('logger')->info('Preparing to store user details');

    $storage_result = [];
    $store_result = '';
    $settings = $app->getContainer()->get('settings');
    $database_connection_settings = $settings['pdo_settings'];
    $doctrine_queries = $app->getContainer()->get('sqlQueries');
    $database_connection = $app->getContainer()->get('databaseWrapper');
    $userModel = $app->getContainer()->get('userModel');


    $userModel->setUsername($cleaned_parameters['sanitised_username']);
    $userModel->setEmail($cleaned_parameters['sanitised_email']);
    $userModel->setPassword($hashed_password);

    $app->getContainer()->get('errorHandler')->forceExceptions();

    try{
        $userModel->setDatabaseConnectionSettings($database_connection_settings);
        $userModel->setDatabaseWrapper($database_connection);
        $userModel->setSqlQueries($doctrine_queries);
        $userModel->storeUserInDatabase();
    }
    catch (Exception $e) {
        $app->getContainer()->get('logger')->warning($e->getMessage());
        $app->getContainer()->get('logger')->warning($e);
        die('Whoops! An error occured trying to register that user');
    }

    $app->getContainer()->get('errorHandler')->restorePrevious();

    return $store_result;
}
