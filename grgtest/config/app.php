<?php

$cnf['namespaces']['Controllers'] = 'C:\xampp\htdocs\code\sample_mvc\grgtest\Controllers\\';
$cnf['default_controller'] = 'index';
$cnf['default_method'] = 'index';

$cnf['session']['autostart'] = TRUE;
$cnf['session']['type'] = 'database';
$cnf['session']['name'] = '__sess';
$cnf['session']['lifetime'] = 3600;
$cnf['session']['path'] = '/';
$cnf['session']['domain'] = '';
$cnf['session']['secure'] = FALSE;
$cnf['session']['dbConnection'] = 'default';
$cnf['session']['dbTable'] = 'session';

return $cnf;
