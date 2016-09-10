<?php

namespace GRG\DB;

class SimpleDB {

    protected $connectionId = 'default';

    /**
     * @var \PDO
     */
    private $db = NULL;

    /**
     * Prepared statement.
     *
     * @var \PDOStatement
     */
    private $stmt = NULL;
    private $params = array();
    private $sql;
    
    public function __construct($connection = NULL) {
        if ($connection instanceof \PDO) {
            $this->db = $connection;
        }
        elseif ($connection != NULL) {
            $this->db = \GRG\App::getInstance()->getDBConnection($connection);
            $this->connectionId = $connection;
        }
        else {
            $this->db = \GRG\App::getInstance()->getDBConnection($this->connectionId);
        }
    }

    /**
     * @param $sql
     * @param array $params
     * @param array $pdoOptions
     *
     * @return \GRG\DB\SimpleDB
     */
    public function prepare($sql, $params = array(), $pdoOptions = array()) {
        $this->stmt = $this->db->prepare($sql, $pdoOptions);
        $this->params = $params;
        $this->sql = $sql;

        return $this;
    }

    /**
     * @param array $params
     *
     * @return \GRG\DB\SimpleDB
     */
    public function execute($params = array()) {
        if ($params) {
            // Overwrite params.
            $this->params = $params;
        }

        $this->stmt->execute($this->params);

        return $this;
    }

    public function fetchAllAssoc() {
        return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function fetchRowAssoc() {
        return $this->stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function fetchAllNum() {
        return $this->stmt->fetchAll(\PDO::FETCH_NUM);
    }

    public function fetchRowNum() {
        return $this->stmt->fetch(\PDO::FETCH_NUM);
    }

    public function fetchAllObj() {
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function fetchRowObj() {
        return $this->stmt->fetch(\PDO::FETCH_OBJ);
    }

    public function fetchAllColumn($column) {
        return $this->stmt->fetchAll(\PDO::FETCH_COLUMN, $column);
    }

    public function fetchRowColumn($column) {
        return $this->stmt->fetch(\PDO::FETCH_BOUND, $column);
    }

    public function fetchAllClass($class) {
        return $this->stmt->fetchAll(\PDO::FETCH_CLASS, $class);
    }

    public function fetchRowClass($class) {
        return $this->stmt->fetch(\PDO::FETCH_BOUND, $class);
    }

    public function getLastInsertedId() {
        return $this->db->lastInsertId();
    }

    public function getAffectedRows() {
        return $this->stmt->rowCount();
    }

    public function getSTMT() {
        return $this->stmt;
    }

}
