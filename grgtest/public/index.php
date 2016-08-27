<?php

include '../../grg/App.php';
$app = \GRG\App::getInstance();

// If we need a different than default router.
//$app->setRouter('SomeRouter');


$app->run();

