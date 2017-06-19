<?php

include(dirname(__FILE__) . '/../private/config/config.php');

try /* to validate and set all the input data */
{
	/* POST requests only! */
	if ($_SERVER['REQUEST_METHOD'] != 'POST')
	{
		throw new Exception('Missing POST data');
	}

	$ingest = new l_ingest($defaults);
	
	$ingest->setMethod($_POST['endpoint']['method']);
	$ingest->setURL($_POST['endpoint']['url']);
	$ingest->setData($_POST['data']);
}
catch (Exception $ex)
{
	header('HTTP/1.1 400 Bad Request');
	echo json_encode(array('message' => $ex->getMessage()), JSON_PRETTY_PRINT);
	die();
}

try /* to merge the URL data */
{
	$ingest->merge();
}
catch (Exception $ex)
{
	header('HTTP/1.1 400 Bad Request');
	echo json_encode(array('message' => $ex->getMessage()), JSON_PRETTY_PRINT);
	die();
}

$queue = new m_queue();
$queue->addTask($ingest->getResult());

echo json_encode($ingest->getResult(), JSON_PRETTY_PRINT);

/* ?> */
