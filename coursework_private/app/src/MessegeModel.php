<?php

/**
 * MessageModel.php
 *
 * File to do all background processing for messages in the app.
 *
 * @author Joe
 */

namespace Coursework;


use phpDocumentor\Reflection\Types\Array_;

class MessegeModel
{
    private $soapWrapper;
    private $result;
    private $count;
    private $database_wrapper;
    private $sql_queries;
    private $database_connection_settings;
    private $app;

    function __construct($app = null)
    {
        $this->soapWrapper = null;
        $this->detail = '';
        $this->result = [];
        $this->count = 500;
    }

    function __destruct()
    {
    }

    /**
     * @param mixed $database_connection_settings
     */
    public function setDatabaseConnectionSettings($database_connection_settings)
    {
        $this->database_connection_settings = $database_connection_settings;
    }

    /**
     * @param mixed $sql_queries
     */
    public function setSqlQueries($sql_queries)
    {
        $this->sql_queries = $sql_queries;
    }

    /**
     * @param mixed $database_wrapper
     */
    public function setDatabaseWrapper($database_wrapper)
    {
        $this->database_wrapper = $database_wrapper;
    }

    /**
     * @param mixed $soapWrapper
     */
    public function setSoapWrapper($soapWrapper)
    {
        $this->soapWrapper = $soapWrapper;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Performs soap call
     *
     * @return Array(obj)
     */
    function retrieveMessages() {
        $this->log('Building soap client');
        $soapResult = null;

        $soapHandle = $this->soapWrapper->buildSoapClient();

        if($soapHandle !== null) {
            $this->log('Soap handle is not null, making soap call');
            $params = $this->setParams();
            $reqFunction = $params['required_service'];
            $soapParams = $params['service_parameters'];
            //$soapParams = implode(', ', $soapParams);
            $soapValue = 'returnMsgs';

            $soapResult = $this->soapWrapper->makeSoapCall($soapHandle, $reqFunction, $soapParams, $soapValue);
        }
        else {
            $this->log('Soap handle is null');
        }

        $this->result = $soapResult;
    }

    /**
     * Setparams()
     *
     * Sets the parameters required to make the soap call
     *
     * @return array
     */
    function setParams() {
        $params = [];

        $params['required_service'] = 'peekMessages';
        $params['service_parameters'] = [
            'username' => M2MUSER,
            'password' => M2MPASSWORD,
            'count' => $this->count
        ];

        return $params;
    }

    /**
     * filterMessages()
     * Filters messages so only ones relevant to this team get saved
     *
     * @return array
     */
    function filterMessages() {
        $output = [];
        $messages = $this->result;
        if (is_array($messages)) {
            $this->log('Filtering messages: ' . count($messages) . ' total');
        }
        else {
            $this->log('Warning, the messages are not an array! Cannot filter messages!');
        }

        foreach ($messages as $msg) {
            $this->log(print_r($msg, true));
            if ($msg->message->id == "20-3110-AI") {
                array_push($output, $msg);
            }
        }
        return $output;
    }

    function getAllMessages() {
        $output = [];
        $query = $this->sql_queries->getAllMessages();
        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);

        $this->database_wrapper->makeDatabaseConnection();
        $this->database_wrapper->safeQuery($query);

        $numberOfReturns = $this->database_wrapper->countRows();

        if ($numberOfReturns > 0) {
            while ($row = $this->database_wrapper->safeFetchArray()) {
                array_push($output, $row);
            }
        } else {
            $output = [];
        }
        return $output;
    }

    /**
     * Wrapper for the logger
     * @param  string $message Message to write at the INFO log level
     * @return [type]          [description]
     */
    function log(string $message){
        if (!is_null($this->app)) {
            try{
                $this->app->getContainer()->get('logger')->info('MessegeModel.php: ' . $message);
            }
            catch (Exception $e){
                throw new \Exception('The log() method in the MessegeModel.php file encountered an error while trying to write to the log!');
            }
        }
    }

    /**
     * App injection method
     * @param [type] $app Instance of Slim App to be injected
     */
    function setAppDI($app) {
        $this->app = $app;
    }

}
