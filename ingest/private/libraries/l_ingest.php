<?php
/**
 * Ingest and validate URL data
 *
 * When data is added to the object, validation rules
 * are applied and exceptions will be thrown if something is amiss.
 * 
 * Returned data is after the {} replacements.
 *
 * @author  Andrew Wells <andrew@wellsie.net>
*/

class l_ingest
{
	private $url;
	private $method;
	private $keys;
	
	private $defaults;
	
	/**
	 * Init some default variables and load defaults (if present).
	 *
	 * @param array $defaults  A collection of default keys/vals.
	*/
	public function __construct($defaults = array())
	{
		/* initialize to empty values */
		$this->url = false;
		$this->keys = array();
		
		/* defaults can be loaded from an external file (config/defaults.php) */
		$this->defaults = $defaults;
	}
	
	/**
	 * Method is the HTTP method (GET, POST, PUT, HEAD, etc).
	 * 
	 * Initial support it GET/POST only
	 *
	 * @param string $method  The HTTP method used for delivery.
	*/
	public function setMethod(&$method)
	{
		/* must pass an HTTP delivery method */
		if (empty($method)) throw new Exception('MISSING: endpoint.method');
		
		/* change the method list in ingest/private/config/config.php */
		$methods = explode(',', KOCHAVA_INGEST_METHODS);
		$methods = array_map('trim', $methods);
		
		if (array_search($method, $methods) === false)  throw new Exception('INVALID: endpoint.method');
		
		/* validation done, save to object */
		$this->method = $method;
	}
	
	/**
	 * URL where the delivery is sent
	 *
	 * @param string $url  The delivery URL, may contain {keys} to be replaced later.
	*/
	public function setURL(&$url)
	{
		/* must pass a URL */
		if (empty($url)) throw new Exception('MISSING: endpoint.url');
		/* URL validation moved to after the merge happens, 
			since {keys} within the URL will not validate */
		
		/* validation done, save to object */
		$this->url = $url;
	}
	
	/**
	 * Method is the HTTP method (GET, POST, PUT, HEAD, etc).
	 * 
	 * @param array $data  Array of key/val replacements to be made to the URL.
	*/
	public function setData(&$data)
	{
		/* remove this if data key/vals are not required */
		if (!isset($data)) throw new Exception('MISSING: data');
		
		/* add (and validate) each key/val pair separately */
		if (is_array($_POST['data']))
		foreach ($_POST['data'] as $k => $v)
		{
			$this->addVal($k, $v);
		}
	}
	
	
	/**
	 * Individual string replacements to be made to the URL
	 * 
	 * @param string $key  Something like {key} in the URL.
	 * @param string $val  What to replace {key} with.
	*/
	private function addVal($key, $val)
	{
		/* currently only the value can be empty */
		if (empty($key)) throw new Exception('MISSING: data.[key]');
		
		/* validation done, save to object */
		$this->keys[$key] = $val;
	}
	
	
	/**
	 * Run the key/val replacement
	*/
	public function merge()
	{
		/* find all the {keys} in the URL */
		preg_match_all('#\{(.*?)\}#', $this->url, $matches);
		
		foreach ($matches[1] as $match)
		{
			/* hopefully the {key} is defined in the POST data or defaults */
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
	
	/**
	 * Return the matched URL and the validated HTTP method
	 * 
	 * @return array The data set needed for delivery
	*/
	public function getResult()
	{
		/* let's not get ahead of ourselves */
		if ($this->url === false) throw new Exception('FATAL: url export attempt before merge');
		
		/* return fields needed to make the delivery */
		return array('method' => $this->method, 'url' => $this->url);
	}
	
	/**
	 * Return the matched URL and the validated HTTP method (in JSON format)
	 * 
	 * @return array The data set needed for delivery
	*/
	public function toJSON()
	{
		return json_encode($this->getResult(), JSON_PRETTY_PRINT);
	}
}

/* ?> */
