<?php

namespace Gandalf;

class Helper 
{
    public static function dynamic_method_exists($methodName, $object)
    {
        if (method_exists($object, $methodName)) {
            return true;
        }

        if (!is_object($object) || 
            !(isset(class_uses($object)['Gandalf\Entity\Caller']))) {
            throw new \InvalidArgumentException('Trying check if method exists in a non object Caller');
        }

        $reflect = new \ReflectionClass($object);
        $method = $reflect->getMethod('getCalleByPattern');
        $method->setAccessible(true);
        $calle = $method->invoke($object, $methodName);
        
        return $calle != null;
    }    
}

