<?php


namespace GRG;

final class Loader {

    private static $namespaces = array();

    private function __construct() {

    }

    public static function registerAutoload() {
        spl_autoload_register(array("\GRG\Loader", 'autoload'));
    }

    public static function autoload($class) {
        self::loadClass($class);
    }

    public static function loadClass($class) {
        foreach (self::$namespaces as $namespace => $path) {
            if (strpos($class, $namespace) === 0) {
                $file = realpath(substr_replace(str_replace('\\', DIRECTORY_SEPARATOR, $class), $path, 0, strlen($namespace)) . '.php');
                if ($file && is_readable($file)) {
                    include $file;
                } else {
                    throw new \Exception('File cannot be included: ' . $file);
                }
                break;
            }
        }
    }

    public static function registerNamespace($namespace, $path) {
        $namespace = trim($namespace);
        if (strlen($namespace) > 0) {
            if (!$path) {
                throw new \Exception('Invalid path.');
            }

            $_path = realpath($path);
            if ($_path && is_dir($_path) && is_readable($_path)) {
                self::$namespaces[$namespace . '\\'] = $_path . DIRECTORY_SEPARATOR;
            } else {
                throw new \Exception('Namespace directory read error: ' . $path);
            }
        } else {
            // TODO: Exception handling
            throw new \Exception('Invalid namespace: ' . $namespace);
        }
    }

    public static function registerNamespaces($nameSpaces) {
        if (is_array($nameSpaces)) {
            foreach ($nameSpaces as $namespace => $path) {
                self::registerNamespace($namespace, $path);
            }
        } else {
            throw new \Exception('Invalid namespaces.');
        }
    }
}
