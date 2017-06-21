<?php
/**
 * Send delivery instructions and data to Redis queue
 *
 * Delivery to job queue can be switched to a different solution (memcache, etc) 
 * by editing this one file.
 * 
 * Pretty sure I'm not doing this right.  This resource mentions using a list:
 * https://blog.logentries.com/2016/05/queuing-tasks-with-redis/
 *
 * @author  Andrew Wells <andrew@wellsie.net>
*/

class m_queue
{
	private $redis;
	
	/**
	 * Make connection to Redis server (values set in ingest/private/config/config.php)
	*/
	public function __construct()
	{
		$this->redis = new Redis(); 
		$this->redis->connect(REDIS_ADR, REDIS_PORT);
		$this->redis->auth(REDIS_AUTH);
	}
	
	/**
	 * Publish task to the queue
	 * 
	 * @param object $task the item to post (will be JSON-encoded)
	*/
	public function addTask($task)
	{
		//$this->redis->lpush('waitQueue', json_encode($task));
		$this->redis->publish('waitQueue', json_encode($task));
	}
}
