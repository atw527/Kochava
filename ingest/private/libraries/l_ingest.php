<?php

class l_ingest
{
	private $url;
	private $method;
	private $keys;
	
	private $defaults;
	
	public function __construct($defaults)
	{
		$this->url = false;
		$this->keys = array();
		
		$this->defaults = $defaults;
	}
	
	public function __destruct()
	{
	
	}
	
	public function setMethod(&$method)
	{
		if (empty($method)) throw new Exception('MISSING: endpoint.method');
		
		$methods = explode(',', KOCHAVA_INGEST_METHODS);
		$methods = array_map('trim', $methods);
		
		if (array_search($method, $methods) === false)  throw new Exception('INVALID: endpoint.method');
		
		$this->method = $method;
	}
	
	public function setURL(&$url)
	{
		if (empty($url)) throw new Exception('MISSING: endpoint.url');
		// URL validation moved to after the merge happens
		
		$this->url = $url;
	}
	
	public function setData(&$data)
	{
		if (!isset($data)) throw new Exception('MISSING: data');
		if (is_array($_POST['data'])) foreach ($_POST['data'] as $k => $v)
		{
			$this->addVal($k, $v);
		}
	}
	
	private function addVal($key, $val)
	{
		if (empty($key)) throw new Exception('MISSING: data.[key]');
		/* null values are allowed */
		
		$this->keys[$key] = $val;
	}
	
	public function merge()
	{
		preg_match_all('#\{(.*?)\}#', $this->url, $matches);
		
		foreach ($matches[1] as $match)
		{
			if (isset($this->keys[$match]))
			{
				$this->url = str_replace('{' . $match . '}', $this->keys[$match], $this->url);
			}
			else if (isset($this->defaults[$match]))
			{
				$this->url = str_replace('{' . $match . '}', $this->defaults[$match], $this->url);
			}
			else
			{
				throw new Exception('Key not found and no default set: ' . $match);
			}
		}
		
		/* now that all the replacements are done, are we left with a valid URL? */
		if (filter_var($this->url, FILTER_VALIDATE_URL) === false) throw new Exception('INVALID: endpoint.url');
	}
	
	public function getResult()
	{
		return array('method' => $this->method, 'url' => $this->url);
	}
}

/* ?> */
