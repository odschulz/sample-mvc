<?php


namespace GRG;


class View {
    private static $_instance = NULL;
    private $viewPath = NULL;
    private $viewDir = NULL;
    private $data = array();
    private $extension = '.php';
    
    private function __construct() {
        if (isset(\GRG\App::getInstance()->getConfig()->app['viewsDirectory'])) {
            $this->viewPath = \GRG\App::getInstance()->getConfig()->app['viewsDirectory'];
        } else {
            $this->viewPath = realpath('../views/');
        }
    }

    /**
     * @return \GRG\View.
     */
    public static function getInstance() {
        if (self::$_instance == NULL) {
            self::$_instance = new \GRG\View();
        }
        return self::$_instance;
    }

    public function setViewDirectory($path) {
        $path = trim($path);
        if ($path) {
            $path = realpath($path) . DIRECTORY_SEPARATOR;
            if (is_dir($path) && is_readable($path)) {
                $this->viewDir = $path;
            } else {
                throw new \Exception('Wrong viewpath.', 500);
            }
        } else {
            throw new \Exception('Wrong viewpath.', 500);
        }
    }

    /**
     * @param string $name
     *   View name. Separate directories with '.'.
     * @param array $data
     *   Data to pass to view. Will be available in '$this' within the view.
     * @param bool $returnAsString
     *   Return the view result as string.
     *
     * @return string
     */
    public function display($name, $data = array(), $returnAsString = FALSE) {
        if (is_array($data) && !empty($data)) {
            $this->data = array_merge($this->data, $data);
        }
        
        if ($returnAsString) {
            return $this->_includeFile($name);
        } else {
            echo $this->_includeFile($name);
        }
    }

    function __set($name, $value) {
        $this->data[$name] = $value;
    }

    function __get($name) {
        return $this->data[$name];
    }

    private function _includeFile($file) {
        if ($this->viewDir == NULL) {
            $this->setViewDirectory($this->viewPath);
        }
        $p = str_replace('.', DIRECTORY_SEPARATOR, $file);
        $fl = $this->viewDir . $p . $this->extension;
        if (file_exists($fl) && is_readable($fl)) {
            ob_start();
            include $fl;
            return ob_get_clean();
        } else {
            throw  new \Exception('View ' . $file . ' cannot be included', 500);
        }
    }

}
