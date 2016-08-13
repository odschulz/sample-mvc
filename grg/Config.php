<?php

namespace GRG;

class Config {

    private static $_instance = NULL;
    private $_configArray = array();
    private $_configFolder = NULL;
    
    public function __construct() {
        
    }

    public static function getInstance() {
        if (self::$_instance == NULL) {
            self::$_instance = new \GRG\Config();
        }

        return self::$_instance;
    }

    public function getConfigFolder() {
        return $this->_configFolder;
    }

    public function setConfigFolder($configFolder) {
        if (!$configFolder) {
            throw new \Exception('Empty config folder path');
        }
        $_configFolder = realpath($configFolder);

        if (
            $_configFolder != FALSE &&
            is_dir($_configFolder) &&
            is_readable($_configFolder)
        ) {
            // Clear old config data.
            $this->_configArray = array();
            $this->_configFolder = $_configFolder . DIRECTORY_SEPARATOR;
            $nameSpaces = $this->app['namespaces'];
            // TODO: Set namespaces.
            if (is_array($nameSpaces)) {
                \GRG\Loader::registerNamespaces($nameSpaces);
            }
        } else {
            throw new \Exception('Configuration directory read error: ' . $_configFolder);
        }
    }

    public function includeConfigFile($path) {
        if (!$path) {
            throw new \Exception('Error when loading config file.');
        }

        $_file = realpath($path);
        if (
            $_file != FALSE &&
            is_file($_file) &&
            is_readable($_file)
        ) {
//            var_dump(basename($_file));die;
            $_basename = explode('.php', basename($_file))[0];
            $this->_configArray[$_basename] = include $_file;
        } else {
            throw new \Exception('Config file read error: ' . $path);
        }
    }

    public function __get($name) {
        if (!array_key_exists($name, $this->_configArray)) {
            $this->includeConfigFile($this->_configFolder . $name . '.php');
        }

        if (array_key_exists($name, $this->_configArray)) {
            return $this->_configArray[$name];
        }

        return NULL;
    }

}
