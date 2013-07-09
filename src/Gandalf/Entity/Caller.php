<?php

namespace Gandalf\Entity;

Trait Caller 
{
	protected $methods = array();

	public function def($fnPatternName, $function)
	{
		$this->methods[$fnPatternName] = new Calle($function, $fnPatternName);

		return $this;
	}

	public function short($fnPatternName, Array $calls)
	{
		$this->methods[$fnPatternName] = new CalleShort($calls);

		return $this;
	}

	public function copy($calle, $object)
	{
		if (!is_object($object) || 
            !(isset(class_uses($object)['Gandalf\Entity\Caller']))) {
            throw new \InvalidArgumentException('Trying access method of a non object Caller');
        }

        $clone = clone $calle;
        $object->def($calle->getPattern(), $calle);
	}

	public function move($calle, $object)
	{
		$this->copy($calle, $object);
		unset($this->methods{$calle->getPattern()});
	}

	public function __set($name, $function)
	{
		$this->def($name, $function);
	}

	public function __get($name)
	{
		return $this->getCalleByPattern($name);
	}

	public function __call($name, $params)
	{
		$calle = $this->getCalleByPattern($name);
		
		if (!$calle) {
			throw new \BadMethodCallException("Method {$name} not exists");
		}
		
		return $calle($params);
	}

	protected function getCalleByPattern($name)
	{
		if (array_key_exists($name, $this->methods)) {
			return $this->methods[$name];
		}

		$output_matches = array();
		foreach ($this->methods as $pattern => $calle) {
			if (preg_match("/^$pattern$/", $name, $output_matches)) {
				$calle->matches = $output_matches;
				//$calle = $this->setExpressionsVars($calle, $output_matches);
				return $calle;
			}
		}

		return null;
	}

	// protected function setExpressionsVars(Calle $calle, Array $output_matches)
	// {
	// 	if (count($output_matches) > 1) {
	// 		array_shift($output_matches);
	// 		// exists groups expressions
	// 		foreach ($output_matches as $key => $match) {
	// 			$index = $key + 1;
	// 			$varName = "_{$index}";
	// 			$calle->$varName = $match[0];
	// 			//$calle->addParameter($index, $match);
	// 		}
	// 	}
	// 	return $calle;
	// }
}