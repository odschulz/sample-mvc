<?php


namespace GRG\Routers;


class DefaultRouter {
    private $_controller = NULL;
    private $_method = NULL;
    private $_params = array();

    public function parse() {
        // TODO: Snippet.
//        echo '<pre>' . print_r($_SERVER, true) . '</pre>';
        $_uri = substr($_SERVER['PHP_SELF'], strlen($_SERVER['SCRIPT_NAME']) + 1);
        $_params = explode('/', $_uri);
        if (isset($_params[0]) && $_params[0]) {
            $this->_controller = ucfirst($_params[0]);

            // If we do not have a controller and method, we do not have params.
            if (isset($_params[1]) && $_params[1]) {
                $this->_method = $_params[1];
                unset($_params[0], $_params[1]);
                $this->$_params = array_values($_params);
            }
        }

        echo $this->getController() . '<br>' . $this->getMethod();

    }

    public function getController() {
        return $this->_controller;
    }

    public function getMethod() {
        return $this->_method;
    }

    public function getGet() {
        return $this->_params;
    }
}
