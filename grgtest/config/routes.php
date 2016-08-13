<?php

//$cnf['admin/users']['namespace'] = 'Controllers\Admin\Users';
//
//$cnf['administration']['namespace'] = 'Controllers\Admin';
//$cnf['administration']['controllers']['new'] = 'create';
//
//$cnf['*']['namespace'] = 'Controllers';
//$cnf['*']['controllers'] = 'alabala';

$cnf['admin']['namespace'] = 'Controllers\Admin\Users';

$cnf['administration']['namespace'] = 'Controllers\Admin';
$cnf['administration']['controllers']['new']['to'] = 'rewrittenController';
$cnf['administration']['controllers']['new']['methods']['new'] = 'create';

$cnf['*']['namespace'] = 'Controllers';
$cnf['*']['controllers'] = 'alabala';

return $cnf;
