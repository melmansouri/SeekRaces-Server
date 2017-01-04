<?php

namespace app\connection;

class ConnectionPDO {

    const HOST = 'localhost';
    const DATABASE = 'seekraces';
    const USER = 'www-data';
    const PASSWORD = 'www-data';

    private static $instance;
    private $connection;

    private function __construct() {
        $this->connection = new \PDO('mysql:host=' . self::HOST . ';dbname=' . self::DATABASE . ';charset=utf8', self::USER, self::PASSWORD);
        $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->connection->setAttribute(\PDO::ATTR_PERSISTENT, false);
    }

    public static function getInstance() {
        try {
            if (!isset(self::$instance)) {
                self::$instance = new \app\connection\ConnectionPDO();
            }
        } catch (Exception $ex) {
            throw $ex;
        }
        return self::$instance;
    }

    public function executeQueryWithData($statement, $data) {
        $sql = $this->connection->prepare($statement);
        return $sql->execute($data);
    }
    
    public function executeQueryWithoutData($statement) {
        $sql = $this->connection->prepare($statement);
        return $sql->execute();
    }

}
