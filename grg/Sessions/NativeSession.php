<?php

namespace GRG\Sessions;

class NativeSession implements \GRG\Sessions\ISession  {
    public function __construct($name, $lifetime = 3600, $path  = NULL, $domain = NULL, $secure = FALSE) {
        if (strlen($name) < 1) {
            $name = '_sess';
        }

        session_name($name);
        // $httponly (5th param) should always be TRUE
        // (means that session cookie is not accessible through JS).
        session_set_cookie_params($lifetime, $path, $domain, $secure, TRUE);
        session_start();
    }

    public function getSessionId() {
        return session_id();
    }

    public function saveSession() {
        session_write_close();
    }

    public function destroySession() {
        session_destroy();
    }

    public function __get($name) {
        return $_SESSION[$name];
    }

    public function __set($name, $value) {
        $_SESSION[$name] = $value;
    }
}
