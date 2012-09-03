<?php

namespace ZendServerAPI;

abstract class Method {
    /**
     * Method for the current action
     * @var string GET|POST 
     */
	protected $method = null;
	/**
	 * Path to method on zendserver api
	 * @var string 
	 */
	protected $functionPath = null;
	/**
	 * Mapper for the result
	 * @var \ZendServerAPI\Mapper\Mapper
	 */
	protected $parser = null;
	
	/**
	 * Base constructor for the method implementations
	 */
	public function __construct()
	{
		static::configure();
	}
	
	/**
	 * Set method for the api call
	 * 
	 * @param string GET|POST
	 */
	public function setMethod($method)
	{
		$this->method = $method;
	}
	
	/**
	 * Get method for the api call
	 * 
	 * @return string
	 */
	public function getMethod()
	{
		return $this->method;
	}
	
	/**
	 * Set the implementation for the result mapping
	 * 
	 * @param \ZendServerAPI\Mapper\Mapper $parser for result mapping
	 */
	public function setParser(\ZendServerAPI\Mapper\Mapper $parser)
	{
	    $this->parser = $parser;
	}
	
	/**
	 * Get class for result mapping
	 * 
	 * @return \ZendServerAPI\Mapper\Mapper
	 */
	public function getParser()
	{
	    return $this->parser;
	}
	
	/**
	 * Setter for the function path
	 * 
	 * @param string $functionPath e.g. /ZendServerManager/Api/Foo
	 */
	public function setFunctionPath($functionPath)
	{
		$this->functionPath = $functionPath;
	}
	
	/**
	 * Getter for the path to server method
	 * 
	 * @return string $functionPath
	 */
	public function getFunctionPath()
	{
		return $this->functionPath;
	}

	/**
	 * Get result from parser
	 * 
	 * @param string $xml
	 */
	public function parseResponse($xml)
	{
	    return $this->getParser()->parse($xml);
	}
	
	/**
	 * Get link for the method
	 * 
	 * @return string
	 */
	public function getLink()
	{
	    return $this->getFunctionPath();
	}
	
	/**
	 * Configures all needed information for the method implementation
	 */
	abstract function configure();
}

?>