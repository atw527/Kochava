<?php
// https://blog.logentries.com/2016/05/queuing-tasks-with-redis/
class m_queue
{
	private $redis;
	
	public function __construct()
	{
		$this->redis = new Redis(); 
		$this->redis->connect('127.0.0.1', 31098);
		$this->redis->auth('Isl5dAscpjt91rQFxtoGTVZtCn1P0K0ycXgRXPLs8ill30Sz36Dl0nOMWgJSqpYV');
	}
	
	public function __destruct()
	{
	
	}
	
	public function addTask($task)
	{
		$this->redis->lpush('waitQueue', json_encode($task));
		$this->redis->publish('waitQueue', json_encode($task));
	}
}
