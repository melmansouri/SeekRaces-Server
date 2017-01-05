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

    /**
     * Prepara y ejecuta la consulta que se le pasa como 
     * parametro
     * @param type $statement La consulta
     * @param type $data Array de datos por los que se va a filtrar en la consulta
     * @return type boolean
     */
    public function executeQueryWithData($statement, $data) {
        $sql = $this->connection->prepare($statement);
        return $sql->execute($data);
    }
    
    /**
     * Prepara y ejecuta la consulta 
     * que se le pasa como parametro
     * @param type $statement
     * @return type boolean
     */
    public function executeQueryWithoutData($statement) {
        $sql = $this->connection->prepare($statement);
        return $sql->execute();
    }
    
    public function executeQueryWithoutDataFetch($statement) {
        $sql = $this->connection->prepare($statement);
        $sql->execute();
        return $sql->fetch(\PDO::FETCH_OBJ);
    }
    
    public function executeQueryWithDataFetch($statement, $data) {
        $sql = $this->connection->prepare($statement);
        $sql->execute($data);
        return $sql->fetch(\PDO::FETCH_OBJ);
    }
    
    public function executeQueryWithoutDataFetchAll($statement) {
        $sql = $this->connection->prepare($statement);
        $sql->execute();
        return $sql->fetchAll();
    }
    
    public function executeQueryWithDataFetchAll($statement, $data) {
        $sql = $this->connection->prepare($statement);
        $sql->execute($data);
        return $sql->fetchAll();
    }

}
