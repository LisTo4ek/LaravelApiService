<?php


namespace App\Support;

use \ReflectionClass;
use \ReflectionProperty;

abstract class SimpleDataTransferObject
{

    public function __construct(array $parameters = [])
    {
        $class = new ReflectionClass(static::class);

        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $reflectionProperty){
            $propertyName = $reflectionProperty->getName();
            if (isset($parameters[$propertyName])) {
                $this->{$propertyName} = $parameters[$propertyName];
            }
        }
    }

    public function toArray()
    {
        $class = new ReflectionClass(static::class);

        $response = [];

        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $reflectionProperty){
            $propertyName = $reflectionProperty->getName();
            $response[$propertyName] = $this->{$propertyName};
        }

        return $response;
    }
}
