<?php

namespace DSXI\Apps\DB;

use \PDO, \PDOException;

//
// Database Class
//
class Database
{
    protected $instance;

    // connect
    function __construct($host, $dbname, $user, $pass)
    {
        try
        {
            // create connection string
            $connection = sprintf('mysql:%s', implode(';', [
                'host='. $host,
                'dbname='. $dbname,
                'port=3306',
                'charset=utf8',
            ]));

                // setup connection
            $this->instance = new PDO($connection, $user, $pass, [
                PDO::ATTR_PERSISTENT => true,
            ]);

            // if failed connection
            if (!$this->instance) {
                die('(non-exception) Could not connect to the database');
            }

            $this->instance->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->instance->setAttribute(PDO::ATTR_TIMEOUT, 15);
        }
        catch (PDOException $e)
        {
            show($sql);
            die($e->getMessage());
        }
    }

    //
    // Null connection on destruct
    //
    function __destruct()
    {
        $this->instance = null;
    }

    //
    // Run a query
    //
    public function sql($sql, $binds = [], $isSingle = false)
    {
        $sql = trim(preg_replace('/\s\s+/', ' ', $sql));

        try
        {
            // prepare and execute SQL statement
            $query = $this->instance->prepare($sql);
            $query = $this->handleBinds($query, $binds);
            $query->execute();

            // if any errors, if non, increment SQL count
            $this->handleErrors($query);

            // get action
            $action = $this->getSqlAction($sql);

            // perform action
            if (in_array($action, ['select', 'show', 'alter']))
            {
                return $isSingle ?
                    $query->fetch(PDO::FETCH_ASSOC) :
                    $query->fetchAll(PDO::FETCH_ASSOC);
            }
            else if (in_array($action, ['insert', 'update', 'delete']))
            {
                return $this->instance->lastInsertId();
            }
        }
        catch(PDOException $e)
        {
            show($sql);
            die($e->getMessage());
        }
    }

    //
    // Handle passed binds parameters
    //
    private function handleBinds($query, $binds)
    {
        // check if there are bind params
        if ($binds)
        {
            // append bind params
            foreach($binds as $id => $value)
            {
                $query->bindValue($id, $value);
            }
        }

        return $query;
    }

    //
    // Handle any errors
    //
    private function handleErrors($query)
    {
        // get any errors
        $errors = $query->errorInfo();
        if (isset($errors[2]) && $errors[2]) {
            // TODO : This needs handling much better, maybe a redirect
            die(print_r($errors, true));
        }
    }

    //
    // Get SQL action
    //
    private function getSqlAction($sql)
    {
        return strtolower(explode(' ', $sql)[0]);
    }
}
