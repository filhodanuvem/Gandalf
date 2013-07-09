<?php

namespace Gandalf\Entity;

Class Calle
{
	protected $function; 

	protected $pattern;

	public $matches = array();

	public function __construct($function, $pattern)
	{
		if ($function instanceof \Closure) {
			$function = $function->bindTo($this);
		}
		$this->pattern  = $pattern;
		$this->function = $function;
	}

	public function __invoke($params)
	{
		return call_user_func_array($this->function, $params);
	}

	public function __get($name)
	{
		if (preg_match('/_d+/', $name) === false) {
			throw new \UnexpectedValueException("Atributte {$name} dont exists");
		}
		
		$index = str_replace('_', '', $name);
		if (!array_key_exists($index, $this->matches)) {
			return null;
		}

		return $this->matches[$index];
	}

	public function getPattern()
	{
		return $this->pattern;
	}

	public function getFunction(){
		return $this->function;
	}

}