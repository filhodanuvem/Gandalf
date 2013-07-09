<?php

namespace Gandalf\Entity;

class CalleShort extends Calle
{

    protected $calls;

    protected $params;

    public function __construct(Array $calls)
    {
        $this->calls = $calls;
    }

    public function __invoke($params)
    {
        $this->params = $params;
        $numberCall = 0;
        foreach ($this->calls as $callParams) {
            if (!$callParams) {
                throw new \InvalidArgumentException("Call number {$numberCall} is not valid");
            }

            $fnName = array_shift($callParams);
            if (!function_exists($fnName)) {
                throw new \BadFunctionCallException("Function {$fnName} dont exists");
            }

            $this->activeVars($callParams);
            ++$numberCall;
            $this->{"return".$numberCall} = call_user_func_array($fnName, $callParams);
        }

        return $this->{"return".$numberCall};
    }

    public function __get($name)
    {
        if (preg_match('/^param(\d+)/', $name)) {
            $index = str_replace('param', '', $name);

            return $this->params[$index - 1];
        }

        return parent::__get($name);
    }


    protected function activeVars(Array &$call)
    {
        foreach ($call as &$value) {
            if (!preg_match('/^\:([a-zA-Z_][a-zA-Z_0-9]*)$/', $value)) {
                continue;
            } 
            $varName = preg_replace('/^\:([a-zA-Z_][a-zA-Z_0-9]*)$/', '${1}', $value);
            $value = $this->{$varName};
        }
    }
}