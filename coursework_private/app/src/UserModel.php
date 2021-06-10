<?php


namespace Coursework;


class UserModel
{
    private $username;
    private $email;
    private $password;
    private $database_connection_settings;
    private $database_wrapper;
    private $sql_queries;

    public function __construct()
    {
        $this->username = null;
        $this->email = null;
        $this->password = null;
        $this->database_connection_settings = null;
        $this->database_wrapper = null;
        $this->sql_queries = null;
    }

    public function __destruct() { }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    function setDatabaseWrapper($wrapper) {
      $this->database_wrapper = $wrapper;
   }

    public function setDatabaseConnectionSettings($database_connection_settings)
    {
        $this->database_connection_settings = $database_connection_settings;
    }

    public function setSqlQueries($sql_queries)
    {
        $this->sql_queries = $sql_queries;
    }

    public function storeUserInDatabase()
    {
        $store_result = false;

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
//        $this->database_wrapper->SetLogger($this->session_logger);
        $this->database_wrapper->makeDatabaseConnection();

        $query = $this->sql_queries->storeUser($this->password, $this->username, $this->email);
        $this->database_wrapper->safeQuery($query);

        return "200: OK";
    }

//added this trying to get login to work
    public function retrieveUserData($username) {
        $query = $this->sql_queries->retrieveUserAccount($username);

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $this->database_wrapper->safeQuery($query);
        $user_result = $this->database_wrapper->safeFetchRow();

        return $user_result;
    }
}
