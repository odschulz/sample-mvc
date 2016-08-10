<?php


namespace GRG;


class FrontController {
    private static $_instance = NULL;

    public function __construct() {

    }
    
    public function dispatch() {
        $a = new \GRG\Routers\DefaultRouter();
        $a->parse();
    }

    public static function getInsance() {
        if (self::$_instance == NULL) {
            self::$_instance = new \GRG\FrontController();
        }

        return self::$_instance;
    }
}
