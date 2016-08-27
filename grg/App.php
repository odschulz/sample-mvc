<?php

namespace GRG;

use GRG\Routers\iRouter;

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

        if ($this->router instanceof \GRG\Routers\iRouter) {
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

        $this->_frontController->dispatch();
    }

    /**
     * @return \GRG\Config
     */
    public function getConfig() {
        return $this->_config;
    }

}
