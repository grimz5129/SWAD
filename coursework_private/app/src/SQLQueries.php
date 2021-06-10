<?php

/**
 * SQLQueries.php
 *
 * Stores usefull SQL Queries for use around the app
 *
 * @author Joe
 * @author Yefri
 * @author Nicholas
 */

namespace Coursework;



class SQLQueries
{
    function getAllMessages() : string
    {
        $queryString = "SELECT *";
        $queryString .= "FROM message";
        return $queryString;
    }

    function storeUser($password, $username, $email) : string {
      $queryString = "INSERT INTO users(Username, Password, Email)";
      $queryString .= "VALUES ('" . $username  . "', '" . $password . "', '" . $email . "')";
      return $queryString;
   }

   function retrieveUser($email)
   {
       $queryString = "SELECT *";
       $queryString .= "FROM users";
       $queryString .= "Where email = ‘" . $email ."’";
       return $queryString;
   }

   function retrieveUserAccount($username)
   {
       $queryString = "SELECT * ";
       $queryString .= "FROM users ";
       $queryString .= "WHERE Username = '" . $username ."'";
       return $queryString;
   }

}
