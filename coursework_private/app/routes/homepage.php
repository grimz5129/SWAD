<?php

/**
 * homepage.php
 *
 * Renders the homepage with the login and register forms
 *
 *@author Joe
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/', function(Request $request, Response $response) use ($app) {
    $app->getContainer()->get('logger')->debug('Homepage requested, rendering page');
    $html_output = $this->view->render($response,
       'homepage.html.twig',
       [
           'css_path' => CSS_PATH,
           'page_title' => APP_NAME,
           'landing_page' => LANDING_PAGE,
           //'page_heading_1' => 'Home',
       ]
   );

    $processed_output = processOutput($app, $html_output);

    return $processed_output;
});

function processOutput($app, $html_output) {
    $process_output = $app->getContainer()->get('processOutput');
    $html_output = $process_output->processOutput($html_output);
    return $html_output;
}
