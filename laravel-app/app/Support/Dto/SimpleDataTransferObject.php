<?php


namespace App\Support\Dto;

use \ReflectionClass;
use \ReflectionProperty;

/**
 * Class SimpleDataTransferObject
 * @package App\Support\Dto
 */
abstract class SimpleDataTransferObject
{
    /**
     * SimpleDataTransferObject constructor.
     * @param array $parameters
     */
    public function __construct(array $parameters = [])
    {
        $class = new ReflectionClass(static::class);

        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $reflectionProperty) {
            $propertyName = $reflectionProperty->getName();

            if (isset($parameters[$propertyName])) {
                $this->{$propertyName} = $parameters[$propertyName];
            }
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $class = new ReflectionClass(static::class);

        $response = [];

        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $reflectionProperty) {
            $propertyName            = $reflectionProperty->getName();
            $response[$propertyName] = $this->{$propertyName};
        }

        return $response;
    }
}
