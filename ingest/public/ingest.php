<?php
/**
 * Receive HTTP POST to kick off the delivery request
 *
 * @author  Andrew Wells <andrew@wellsie.net>
*/

/* all config data & path constants */
include(dirname(__FILE__) . '/../private/config/config.php');

try /* to validate and set all the input data */
{
	/* POST requests only! */
	if ($_SERVER['REQUEST_METHOD'] != 'POST')
	{
		throw new Exception('Missing POST data');
	}
	
	/* init the ingest object and validate/add data */
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
	/* merging all the key/vals */
	$ingest->merge();
}
catch (Exception $ex)
{
	header('HTTP/1.1 400 Bad Request');
	echo json_encode(array('message' => $ex->getMessage()), JSON_PRETTY_PRINT);
	die();
}

/* load the object to add the delivery request to the processing queue */
$queue = new m_queue();
$queue->addTask($ingest->getResult());

/* currently have this for debugging purposes */
echo $ingest->toJSON();

/* ?> */
