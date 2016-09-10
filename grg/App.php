<?php

namespace GRG;

use GRG\Routers\IRouter;
use Guzzle\Cache\NullCacheAdapter;

include 'Loader.php';

class App {
    private static $_instance = null;

    /**
     * @var string
     */
    private $router = NULL;

    /**
     * @var \GRG\Config
     */
    private $_config = NULL;

    /**
     * @var \GRG\FrontController
     */
    private $_frontController = NULL;

    private $_dbConnections = array();

    /**
     * @var \GRG\Sessions\ISession.
     */
    private $_session = NULL;

    private function __construct() {
        \GRG\Loader::registerNamespace('GRG', dirname(__FILE__) . DIRECTORY_SEPARATOR);
        \GRG\Loader::registerAutoload();
        $this->_config = \GRG\Config::getInstance();
        // If config folder is not set, use the defaualt one.
        if ($this->_config->getConfigFolder() == NULL) {
            $this->setConfigFolder('../config');
        }
    }

    /**
     * @return \GRG\App
     */
    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new \GRG\App();
        }

        return self::$_instance;
    }

    public function getConfigFolder() {
        return $this->_config->getConfigFolder();
    }

    public function setConfigFolder($path) {
        $this->_config->setConfigFolder($path);
    }

    /**
     * @return string
     */
    public function getRouter(): string {
        return $this->router;
    }

    /**
     * @param string $router
     */
    public function setRouter(string $router) {
        $this->router = $router;
    }


    public function run() {
        // If config folder is not set, use the defaualt one.
        if ($this->_config->getConfigFolder() == NULL) {
            $this->setConfigFolder('../config');
        }

        $this->_frontController = \GRG\FrontController::getInsance();

        if ($this->router instanceof \GRG\Routers\IRouter) {
            $this->_frontController->setRouter($this->router);
        }
        elseif ($this->router == 'SomeRouter') {
            // TODO: create router.
//            $this->_frontController->setRouter(new \GRG\Routers\SomeRouter());
        }
        else {
            // If none specified, set default router.
            $this->_frontController->setRouter(new \GRG\Routers\DefaultRouter());
        }

        $_sess = $this->_config->app['session'];
        if ($_sess['autostart']) {
            if ($_sess['type'] == 'native') {
                $_s = new \GRG\Sessions\NativeSession(
                    $_sess['name'],
                    $_sess['lifetime'],
                    $_sess['path'],
                    $_sess['domain'],
                    $_sess['secure']
                );
            }
            elseif ($_sess['type'] == 'database') {
                $_s = new \GRG\Sessions\DBSession(
                    $_sess['dbConnection'],
                    $_sess['name'],
                    $_sess['dbTable'],
                    $_sess['lifetime'],
                    $_sess['path'],
                    $_sess['domain'],
                    $_sess['secure']
                );
            }
            else {
                throw new \Exception('No valid session.', 500);
            }

            $this->setSession($_s);
        }

        $this->_frontController->dispatch();
    }

    /**
     * @return \GRG\Config
     */
    public function getConfig() {
        return $this->_config;
    }

    public function getDBConnection($connectionId = 'default') {
        if (!$connectionId) {
            throw new \Exception('No valid connection identifier provided.', 500);
        }

        if (isset($this->_dbConnections[$connectionId])) {
            return $this->_dbConnections[$connectionId];
        }

        $_cnf = $this->getConfig()->database;
        if (!isset($_cnf[$connectionId])) {
            throw new \Exception('No valid connection identifier provided.', 500);
        }

        $dbc = new \PDO(
            $_cnf[$connectionId]['connection_uri'],
            $_cnf[$connectionId]['username'],
            $_cnf[$connectionId]['password'],
            $_cnf[$connectionId]['pdo_options']
        );

        $this->_dbConnections[$connectionId] = $dbc;

        return $dbc;
    }

    public function setSession(\GRG\Sessions\ISession $session) {
        $this->_session = $session;
    }

    /**
     * @return \GRG\Sessions\ISession
     */
    public function getSession() {
        return $this->_session;
    }

    public function __destruct() {
        if ($this->_session != NULL) {
            $this->_session->saveSession();
        }
    }

}
