<?php

include '../../grg/App.php';

$app = \GRG\App::getInstance();

$config = \GRG\Config::getInstance();
$config->setConfigFolder('../config');
echo $config->app['test'];
$app->run();
