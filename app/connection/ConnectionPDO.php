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
    }

    public static function getInstance() {
        try {
            if (!isset(self::$instance)) {
                self::$instance=new ConnectionPDO();
            }
        } catch (Exception $ex) {
            throw $ex;
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

}
?>

