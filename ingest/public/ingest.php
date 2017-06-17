<?php

/* POST requests only! */
if ($_SERVER['REQUEST_METHOD'] != 'POST')
{
	header('HTTP/1.1 400 Bad Request');
	echo 'HTTP/1.1 400 Bad Request';
	die();
}

/* ?> */
