<?php

/**
 * Created by PhpStorm.
 * User: Ankush
 * Date: 4/25/2018
 * Time: 11:21 AM
 */
class db
{
    protected $hostname = "localhost";
    protected $username = "root";
    protected $password = "";
    protected $pdo;

    public function __construct()
    {
        try {
            $this->pdo = new PDO("mysql:host=$this->hostname;dbname=test", $this->username, $this->password);
            return $this->pdo;

        } catch (\PDOException $e) {
            // handle the exception here
            return $e->getMessage();
        }

    }

    public function closeConnection()
    {
        $this->pdo = null;
    }
}


?>