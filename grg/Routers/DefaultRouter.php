<?php


namespace GRG\Routers;


class DefaultRouter implements \GRG\Routers\IRouter {
    public function getURI() {
        // TODO: Snippet.
//        echo '<pre>' . print_r($_SERVER, true) . '</pre>';
        return substr($_SERVER['PHP_SELF'], strlen($_SERVER['SCRIPT_NAME']) + 1);
    }
}
