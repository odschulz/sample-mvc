<?php

namespace GRG;

include 'Loader.php';

class App {
    private static $_instance = null;

    private function __construct() {
        \GRG\Loader::registerNamespace('GRG', dirname(__FILE__) . DIRECTORY_SEPARATOR);
        \GRG\Loader::registerAutoload();
    }

    public function run() {
        
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
}
