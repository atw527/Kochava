<?php

/* path and directories */

define('PATH_BASE', realpath(dirname(__FILE__) . '/../../') . '/');

define('PATH_PRIVATE', PATH_BASE . 'private/');
define('PATH_CONFIG',  PATH_PRIVATE . 'config/');
define('PATH_LIBS',    PATH_PRIVATE . 'libraries/');
define('PATH_MODELS',  PATH_PRIVATE . 'models/');

define('PATH_PUBLIC', PATH_BASE . 'public/');

define('KOCHAVA_INGEST_METHODS', 'GET,POST');

define('REDIS_ADR', '127.0.0.1');
define('REDIS_PORT', '31098');
define('REDIS_AUTH', 'Isl5dAscpjt91rQFxtoGTVZtCn1P0K0ycXgRXPLs8ill30Sz36Dl0nOMWgJSqpYV');

include(PATH_CONFIG . 'defaults.php');

/* class autoloads */

spl_autoload_register(function ($name) {
	if (substr($name, 0, 2) == 'l_')
	{
		require_once(PATH_LIBS . $name . '.php');
	}
	else if (substr($name, 0, 2) == 'm_')
	{
		require_once(PATH_MODELS . $name . '.php');
	}
});

/* ?> */
