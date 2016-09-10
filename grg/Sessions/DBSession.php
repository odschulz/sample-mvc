<?php


namespace GRG\Sessions;


class DBSession extends \GRG\DB\SimpleDB implements \GRG\Sessions\ISession {

    private $sessionName;
    private $tableName;
    private $lifetime;
    private $path;
    private $domain;
    private $secure;
    private $sessionId = NULL;
    private $sessionData = array();

    public function __construct(
        $dbConnection,
        $name,
        $tableName = 'session',
        $lifetime = 3600,
        $path  = NULL,
        $domain = NULL,
        $secure = FALSE
    ) {
        parent::__construct($dbConnection);

        $this->sessionName = $name;
        $this->tableName = $tableName;
        $this->lifetime = $lifetime;
        $this->path = $path;
        $this->domain = $domain;
        $this->secure = $secure;
        $this->sessionId = $_COOKIE[$name];

        if (rand(0, 50) == 1) {
            $this->_garbageCollector();
        }

        if (strlen($this->sessionId) < 32) {
            echo 1;
            $this->_startNewSession();
        } else if (!$this->_validateSession()) {
            $this->_startNewSession();
        }
    }

    private function _startNewSession() {
        $this->sessionId = md5(uniqid('grg', TRUE));
        $this->prepare(
            'INSERT INTO ' . $this->tableName . ' (sessid, valid_until) VALUES(?, ?)',
            array(
                $this->sessionId,
                $_SERVER['REQUEST_TIME'] + $this->lifetime
            )
        )
        ->execute();

        setcookie(
            $this->sessionName,
            $this->sessionId,
            ($_SERVER['REQUEST_TIME'] + $this->lifetime),
            $this->path,
            $this->domain,
            $this->secure,
            TRUE
        );
    }

    private function _validateSession() {
        if ($this->sessionId) {
            echo $_SERVER['REQUEST_TIME'] + $this->lifetime;
            $query = $this->prepare(
                'SELECT * FROM ' . $this->tableName . ' WHERE sessid = ? AND valid_until <= ?',
                array(
                    $this->sessionId,
                    $_SERVER['REQUEST_TIME'] + $this->lifetime
                ))
                ->execute()
                ->fetchAllAssoc();
            if (is_array($query) && count($query) == 1 && $query[0]) {
                $this->sessionData = unserialize($query[0]['sess_data']);

                return TRUE;
            }
        }

        return FALSE;
    }

    private function _garbageCollector() {
        $this->prepare('DELETE FROM ' . $this->tableName . ' WHERE valid_until < ?', array($_SERVER['REQUEST_TIME']))->execute();
    }

    public function getSessionId() {
        return $this->sessionId;
    }

    public function saveSession() {
        if ($this->sessionId) {
            $this->prepare(
                'UPDATE ' . $this->tableName . ' SET sess_data = ?, valid_until = ? WHERE sessid = ?',
                array(
                    serialize($this->sessionData),
                    $_SERVER['REQUEST_TIME'] + $this->lifetime,
                    $this->sessionId
                ))
            ->execute();

            setcookie(
                $this->sessionName,
                $this->sessionId,
                ($_SERVER['REQUEST_TIME'] + $this->lifetime),
                $this->path,
                $this->domain,
                $this->secure,
                TRUE
            );
        }
    }

    public function destroySession() {
        if ($this->sessionId) {
            $this->prepare('DELETE FROM ' . $this->tableName . ' WHERE sessid = ?', array($this->sessionId))->execute();
        }
    }

    public function __get($name) {
        return $this->sessionData[$name];
    }

    public function __set($name, $value) {
        $this->sessionData[$name] = $value;
    }

}
