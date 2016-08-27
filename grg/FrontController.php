<?php


namespace GRG;


class FrontController {
    private static $_instance = NULL;
    private $nameSpace = NULL;
    private $controller = NULL;
    private $method = NULL;
    private $router = NULL;

    public function __construct() {

    }

    public function getRouter() {
        return $this->router;
    }

    public function setRouter(\GRG\Routers\iRouter $router) {
        $this->router = $router;
    }

    public function dispatch() {
        if ($this->router == NULL) {
            throw new \Exception('No valid router found', 500);
        }

        $_uri = $this->router->getURI();
        $routes = \GRG\App::getInstance()->getConfig()->routes;

        if (!is_array($routes) || count($routes) < 1) {
            throw new \Exception('Default rounte missing', 500);
        }

        foreach ($routes as $route => $data) {
            if (
                ($_uri == $route || stripos($_uri, $route . '/') === 0) &&
                isset($data['namespace']) &&
                $data['namespace']
            ) {
                $this->nameSpace = $data['namespace'];
                $_uri = substr($_uri, strlen($route) + 1);
                $_route_data = $data;
                break;
            }
        }

        if ($this->nameSpace == NULL && !$routes['*']['namespace']) {
            throw new \Exception('Default route missing', 500);
        }

        if ($this->nameSpace == NULL && $routes['*']['namespace']) {
            $this->nameSpace = $routes['*']['namespace'];
            $_route_data = $routes['*'];
        }

        $_params = explode('/', $_uri);
        if (isset($_params[0]) && $_params[0]) {
            $this->controller = strtolower($_params[0]);
            // If we do not have a controller and method, we do not have params.
            if (isset($_params[1]) && strtolower($_params[1])) {
                $this->method = $_params[1];
            } else {
                $this->method = $this->getDefaultMethod();
            }
        } else {
            $this->controller = $this->getDefaultController();
            $this->method = $this->getDefaultMethod();
        }

        if (
            is_array($_route_data) &&
            isset($_route_data['controllers'])
        ) {
            // Replace controller if it has rewrite.
            if (isset($_route_data['controllers'][$this->controller]['to'])) {
                $this->controller = strtolower($_route_data['controllers'][$this->controller]['to']);
            }
            // Replace method if it has rewrite.
            if (isset($_route_data['controllers'][$this->controller]['methods'][$this->method])) {
                $this->method = strtolower($_route_data['controllers'][$this->controller]['methods'][$this->method]);
            }
        }

        $f = $this->nameSpace . '\\' . ucfirst($this->controller);
        $newController = new $f();
        var_dump($newController);

        echo $this->nameSpace . '<br>';
        echo $this->controller . '<br>';
        echo $this->method . '<br>';
    }

    public static function getInsance() {
        if (self::$_instance == NULL) {
            self::$_instance = new \GRG\FrontController();
        }

        return self::$_instance;
    }

    public function getDefaultController() {
        $config = \GRG\App::getInstance()->getConfig();
        $controller = isset($config->app['default_controller']) ? $config->app['default_controller'] : NULL;
        if ($controller) {
            return strtolower($controller);
        }
        return 'index';
    }

    public function getDefaultMethod() {
        $config = \GRG\App::getInstance()->getConfig();
        $method = isset($config->app['default_method']) ? $config->app['default_method'] : NULL;
        if ($method) {
            return strtolower($method);
        }

        return 'index';
    }

}
