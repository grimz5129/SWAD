<?php

/**
 * messageViewLocal.php
 * A php script to display and store the new soap data in the database
 *
 * @author Joe
 */


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/saveMessages', function (Request $request, Response $response) use ($app) {
  if ($_SESSION['LoggedIn'] === true) {
    $app->getContainer()->get('logger')->info('User logged in, allowing saveMessages to complete');
    $dbMessages = getDBMessages($app);
    $soapMessages = getSoapMessages($app);

    addUnique($app, $dbMessages, $soapMessages);

    $htmlOutput = $this->view->render($response,
        'messegeView.html.twig',
        [
            'local' => true,
            'css_path' => CSS_PATH,
            'page_title' => APP_NAME,
            'landing_page' => LANDING_PAGE,
            'messages' => getDBMessages($app),
        ]
    );
} else {
    $app->getContainer()->get('logger')->notice('User attempted to access saveMessages while not logged in');
    echo '<h1>You are not authorised please login to access this resource</h1>';
}

});

/**
 * addUnique()
 *
 * Adds all new records to the local database
 *
 * @param $app - the app
 * @param $dbMessages - array of messages from the database
 * @param $soapMessages - array of soap messages
 * @return string - an error string
 *
 * @author Joe
 */

function addUnique($app, $dbMessages, $soapMessages) : string
{
    $app->getContainer()->get('logger')->info('addUnique: Merging unique messages from Soap to the local database. Total DBMessage:' . count($dbMessages) . '/Total SOAPMessage:' . count($soapMessages));
    $toStore = [];
    $error = '';

    if(count($dbMessages) > 0) {
        $app->getContainer()->get('logger')->debug('addUnique: DB Messages greater than zero, carrying on');

        foreach ($dbMessages as $dbMessage) {
            $match = false;
            $item = null;

            foreach ($soapMessages as $soapMessage) {
                if ($soapMessage->receivedtime === $dbMessage['receivedDate']) {
                    $match = true;
                } else {
                    $item = $soapMessage;
                }
            }
            if (!$match) {
                array_push($toStore, $item);
            }
        }

        foreach ($toStore as $item) {
            $app->getContainer()->get('logger')->debug('addUnique: Preparing to store new message in DB');
            if (!is_null($item)) {
                $message = new Coursework\Domain\message();
                $em = $app->getContainer()->get('messageRepository');


                $message->setId(1);
                $message->setHeater($item->message->h);
                $message->setMessageRef($item->messageref);
                $message->setReceivedDate($item->receivedtime);
                $message->setTemperature($item->message->h);
                $message->setSwitch4($item->message->s4);
                $message->setSwitch3($item->message->s3);
                $message->setSwitch2($item->message->s2);
                $message->setSwitch1($item->message->s1);
                $message->setKeypad($item->message->kp);
                $message->setReverse($item->message->rv);
                $message->setForward($item->message->fw);
                $message->setFan($item->message->f);
                $message->setBearer($item->bearer);
                $message->setDestMSISDN($item->destinationmsisdn);
                $message->setSrcMSISDN($item->sourcemsisdn);

                try {
                    $em->store($message);
                    $app->getContainer()->get('logger')->info('addUnique: Message stored in DB');
                    $error = '200: OK';

                } catch (Exception $exception) {
                    $app->getContainer()->get('logger')->warning('addUnique: Failed to store message in DB');
                    $app->getContainer()->get('logger')->warning($exception);
                    $error = 'not stored';
                }
            }
            else {
                $app->getContainer()->get('logger')->notice('addUnique: This message was null?... SKIPPING!');
            }
        }
    } else {
        $app->getContainer()->get('logger')->info('addUnique: Checking each soapMessage');
        foreach ($soapMessages as $item) {
            $message = new Coursework\Domain\message();
            $em = $app->getContainer()->get('messageRepository');

            $message->setId(1);
            $message->setHeater($item->message->h);
            $message->setMessageRef($item->messageref);
            $message->setReceivedDate($item->receivedtime);
            $message->setTemperature($item->message->h);
            $message->setSwitch4($item->message->s4);
            $message->setSwitch3($item->message->s3);
            $message->setSwitch2($item->message->s2);
            $message->setSwitch1($item->message->s1);
            $message->setKeypad($item->message->kp);
            $message->setReverse($item->message->rv);
            $message->setForward($item->message->fw);
            $message->setFan($item->message->f);
            $message->setBearer($item->bearer);
            $message->setDestMSISDN($item->destinationmsisdn);
            $message->setSrcMSISDN($item->sourcemsisdn);

            try {
                $em->store($message);
                $app->getContainer()->get('logger')->info('addUnique: Stored soapMessage in DB');
                $error = '200: OK';

            } catch (Exception $exception) {
                $app->getContainer()->get('logger')->warning('addUnique: Failed to store soapMessage in DB');
                $app->getContainer()->get('logger')->warning($exception);
                $error = 'not stored';
            }
        }
    }
    return $error;
}

/**
 * getSoapMessages()
 * Fetches soap messages from the EE M2M server
 *
 * @param $app - The App
 * @return array - An array of messages from the server
 */

function getSoapMessages($app): array {
    $app->getContainer()->get('logger')->info('Getting soap messages from EE');
    $messages = null;

    $soapWrapper = $app->getContainer()->get('soapWrapper');
    $model = $app->getContainer()->get('messageModel');

    $model->setAppDI($app);
    $model->setSoapWrapper($soapWrapper);
    $model->retrieveMessages();

    $messages = $model->filterMessages();

    return $messages;
}

/**
 * getDbMessages()
 *
 * Function to get all the messages from the Database
 *
 * @param $app - The App
 * @return array - An array of messages from the database
 */

function getDBMessages($app) : array {
    $app->getContainer()->get('logger')->info('Getting SOAP messages from DB');
    $db = $app->getContainer()->get('databaseWrapper');
    $message = $app->getContainer()->get('messageModel');
    $queries = $app->getContainer()->get('sqlQueries');

    $settings = $app->getContainer()->get('settings');
    $conn = $settings['pdo_settings'];

    $app->getContainer()->get('logger')->debug('Setting DB connection settings');

    $message->setDatabaseConnectionSettings($conn);
    $message->setSqlQueries($queries);
    $message->setDatabaseWrapper($db);

    $app->getContainer()->get('errorHandler')->forceExceptions();
    try{
        $messages = $message->getAllMessages();
    }
    catch(Exception $e) {
        $app->getContainer()->get('logger')->error($e->getMessage());
        $app->getContainer()->get('logger')->error($e);
        die('Whoops! Could not get soap messages from DB');
    }
    $app->getContainer()->get('errorHandler')->restorePrevious();

    return $messages;
}
