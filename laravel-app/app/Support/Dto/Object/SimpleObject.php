<?php

namespace App\Support\Dto\Object;

use App\Support\Json;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use \ReflectionClass;
use \ReflectionProperty;

/**
 * Class SimpleObject
 * @package App\Support\Dto\Object
 */
abstract class SimpleObject implements Arrayable, Jsonable
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
    public function toArray(): array
    {
        $class = new ReflectionClass(static::class);

        $response = [];

        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $reflectionProperty) {
            $propertyName            = $reflectionProperty->getName();
            $response[$propertyName] = $this->{$propertyName};
        }

        return $response;
    }

    /**
     * @param int $options
     * @return string
     */
    public function toJson($options = 0): string
    {
        return Json::encode($this->toArray(), $options);
    }
}
