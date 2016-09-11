<?php

include '../../grg/App.php';
$app = \GRG\App::getInstance();

// If we need a different than default router.
//$app->setRouter('SomeRouter');

// Get current db connection
//$app->getDBConnection();

// DB example.
//$db = new \GRG\DB\SimpleDB();
//$a = $db->prepare('SELECT * FROM users WHERE uid = ?', array(1))->execute()->fetchAllAssoc();
//var_dump($a);

$app->run();