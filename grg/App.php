<?php

namespace GRG;

include 'Loader.php';

class App {
    private static $_instance = null;

    /**
     * @var \GRG\Config
     */
    private $_config = NULL;

    /**
     * @var \GRG\FrontController
     */
    private $_frontController = NULL;

    private function __construct() {
        \GRG\Loader::registerNamespace('GRG', dirname(__FILE__) . DIRECTORY_SEPARATOR);
        \GRG\Loader::registerAutoload();
        $this->_config = \GRG\Config::getInstance();
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

    public function run() {
        // If config folder is not set, use the defaualt one.
        if ($this->_config->getConfigFolder() == NULL) {
            $this->setConfigFolder('../config');
        }

        $this->_frontController = \GRG\FrontController::getInsance();

        $this->_frontController->dispatch();
    }

    /**
     * @return \GRG\Config
     */
    public function getConfig() {
        return $this->_config;
    }

}
