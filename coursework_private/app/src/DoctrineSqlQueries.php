<?php
/**
 * class to contain all database access using Doctrine's QueryBulder
 *
 * A QueryBuilder provides an API that is designed for conditionally constructing a DQL query in several steps.
 *
 * It provides a set of classes and methods that is able to programmatically build queries, and also provides
 * a fluent API.
 * This means that you can change between one methodology to the other as you want, or just pick a preferred one.
 *
 * From https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/query-builder.html
 *
 * added by Yefri
 */

namespace coursework;

class DoctrineSqlQueries
{
    public function __construct(){}

    public function __destruct(){}

    public static function queryStoreUserData($queryBuilder, array $cleaned_parameters, string $hashed_password)
    {
        $store_result = [];
        $username = $cleaned_parameters['sanitised_username'];
        $email = $cleaned_parameters['sanitised_email'];

        $queryBuilder = $queryBuilder->insert('users')
            ->values([
                'username' => ':name',
                'email' => ':email',
                'password' => ':password',
            ])
            ->setParameters([
                ':name' => $username,
                ':email' => $email,
                ':password' => $hashed_password
            ]);

        $store_result['outcome'] = $queryBuilder->execute();
        $store_result['sql_query'] = $queryBuilder->getSQL();

        return $store_result;
    }

    public static function queryRetrieveUserData($queryBuilder, array $cleaned_parameters)
    {
        $retrieve_result = [];
        $username = $cleaned_parameters['sanitised_username'];

        $queryBuilder
            ->select('password', 'email')
            ->from('users', 'u')
            ->where('username = ' .  $queryBuilder->createNamedParameter($username));

        $query = $queryBuilder->execute();
        $result = $query->fetchAll();

        return $result;
    }
}
