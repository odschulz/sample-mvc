<?php


namespace GRG;


class InputData {
    private static $_instance = NULL;
    private $_get = NULL;
    private $_post = NULL;
    private $_cookies = array();

    public function __construct() {
        $this->_cookies = $_COOKIE;
    }

    /**
     * @return \GRG\InputData
     */
    public static function getInstance() {
        if (self::$_instance == NULL) {
            self::$_instance = new \GRG\InputData();
        }

        return self::$_instance;
    }

    /**
     * @param null $get
     */
    public function setGet($get) {
        if (is_array($get)) {
            $this->_get = $get;
        }
    }

    /**
     * @param null $post
     */
    public function setPost($post) {
        if (is_array($post)) {
            $this->_post = $post;
        }
    }

    public function hasGet($id) {
        return array_key_exists($id, $this->_get);
    }

    public function hasPost($name) {
        return array_key_exists($name, $this->_post);
    }

    public function hasCookie($name) {
        return array_key_exists($name, $this->_cookies);
    }


    public function get($id, $normalize = NULL, $default = NULL) {
        if ($this->hasGet($id)) {
            if ($normalize != NULL) {
                return \GRG\Common::normalize($this->_get[$id], $normalize);
            }
            return $this->_get[$id];
        }

        return $default;
    }

    public function post($name, $normalize = NULL, $default = NULL) {
        if ($this->hasPost($name)) {
            if ($normalize != NULL) {
                return \GRG\Common::normalize($this->_post[$name], $normalize);
            }
            return $this->_post[$name];
        }

        return $default;
    }

    public function cookies($name, $normalize = NULL, $default = NULL) {
        if ($this->hasCookie($name)) {
            if ($normalize != NULL) {
                return \GRG\Common::normalize($this->_cookies[$name], $normalize);
            }
            return $this->_cookies[$name];
        }

        return $default;
    }
}
