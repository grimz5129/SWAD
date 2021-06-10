<?php

/**
 * messageView.php
 * The controller to process and display the data from the EE M2M server
 *
 * @author Joe
 *
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/messegeView',function(Request $request, Response $response) use ($app) {
 // $_SESSION['LoggedIn'] = false;
    if ($_SESSION['LoggedIn'] === true) {
        $app->getContainer()->get('logger')->info('Logged in user has accessed messegeView');
        $messages = getMessages($app);
        $html_output = $this->view->render($response,
            'messegeView.html.twig',
            [
                'css_path' => CSS_PATH,
                'page_title' => APP_NAME,
                'landing_page' => LANDING_PAGE,
                'messages' => $messages,
                'local' => false,
            ]
        );
    } else {
        $app->getContainer()->get('logger')->notice('Attempt made to access messegeView without logging in first');
        echo '<h1>You are not authorised please login to access this resource</h1>';
    }

    return $html_output;
});

function getMessages($app) {
    $app->getContainer()->get('logger')->info('Retrieving data from EE M2M server');
    $messages = null;

    $soapWrapper = $app->getContainer()->get('soapWrapper');
    $model = $app->getContainer()->get('messageModel');

    $model->setSoapWrapper($soapWrapper);
    $model->retrieveMessages();

    $messages = $model->filterMessages();

    return $messages;
}
