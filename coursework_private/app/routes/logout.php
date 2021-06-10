<?php

/**
 * login.php
 *
 * This page will log the user out and
 *
 * @author Yefri
 */

use \PSr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/logout', function(Request $request, Response $response) use ($app) {
$app->getContainer()->get('logger')->info('Logging out user');
session_destroy();
header('location: ./');
exit;
});
