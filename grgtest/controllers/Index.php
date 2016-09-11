<?php

namespace Controllers;

class Index {
    public function index() {
        $view = \GRG\View::getInstance();
        // To define data that will be available in the view, add it to the View
        // class, or pass it to the display() function as 2nd param array.
        $view->username = 'grg';
        $view->display('admin.index', array('foo' => 'bar'));
    }
}
