<?php

declare(strict_types=1);

namespace CastModels;

use ReflectionProperty;
use JsonSerializable;
use stdClass;
use ReflectionNamedType;

abstract class Model implements JsonSerializable
{


    /**
     * @param mixed[]|string $data
     * @return Model[]
     * */
    public static function collection(array|string $data): array
    {
        $collection = [];

        if (empty($data)) {
            return $collection;
        }

        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        foreach ($data as $item) {
            if (! empty($item)) {
                // @phpstan-ignore-next-line
                $collection[] = new static($item);
            }
        }

        return $collection;

    }//end collection()


    /** @param mixed[]|stdClass $data */
    public function __construct(array|stdClass $data=[])
    {
        $this->update($data);

    }//end __construct()


    /** @param mixed[]|stdClass $data */
    public function update(array|stdClass $data=[]): void
    {
        if (empty($data)) {
            return;
        }

        if (is_object($data)) {
            $data = get_object_vars($data);
        }

        foreach ($data as $k => $v) {
            if (isset($v) && property_exists($this, $k)) {
                $this->setProperty($k, $v);
            }
        }

    }//end update()


    /** @return mixed[] */
    public function toArray(): array
    {
        $result = [];

        foreach (get_object_vars($this) as $property => $value) {
            $result[$property] = $this->toArrayHelper($value);
        }

        return $result;

    }//end toArray()


    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return $this->toArray();

    }//end jsonSerialize()


    /** @return string */
    public function __toString()
    {
        $result = json_encode($this);

        return empty($result) ? '' : $result;

    }//end __toString()


    private function setProperty(string $propertyName, mixed $value): void
    {
        $rProperty = new ReflectionProperty($this, $propertyName);
        $rType     = $rProperty->getType();

        if (empty($rType) || !$rType instanceof ReflectionNamedType) {
            return;
        }

        if ($rType->isBuiltin()) {
            $this->setPropertyBuiltIn($rProperty, $propertyName, $value);
            return;
        }

        $this->setPropertyHelper($rType->getName(), $propertyName, $value);

    }//end setProperty()


    private function setPropertyBuiltIn(ReflectionProperty $rProperty, string $propertyName, mixed $value): void
    {
        if (is_object($value)) {
            $value = (array) $value;
        }

        if (is_array($value)) {
            $this->setArray($rProperty, $propertyName, $value);
        }

        $this->$propertyName = $value;

    }//end setPropertyBuiltIn()


    private function setArray(ReflectionProperty $rProperty, string $propertyName, mixed $value): void
    {
        if (empty($value)) {
            return;
        }

        $phpDoc = $rProperty->getDocComment();

        if (! $phpDoc) {
            $this->$propertyName = $value;
            return;
        }

        $className = $this->getClassNameFromPhpDoc($phpDoc);

        if (! strlen($className)) {
            $this->$propertyName = $value;
            return;
        }

        if (method_exists($className, 'tryFrom')) {
            $this->$propertyName = array_map(fn ($v) => $className::tryFrom($v), $value);
            return;
        };

        $array = [];
        foreach ($value as $v) {
            if (! empty($v)) {
                $array[] = new $className($v);
            }
        }

        if (! empty($array)) {
            $this->$propertyName = $array;
        }

    }//end setArray()


    private function getClassNameFromPhpDoc(string $phpDoc): string
    {
        $pattern = '/\s+([\w|\\\\]+)\[/m';
        $matches = [];

        preg_match($pattern, $phpDoc, $matches, PREG_OFFSET_CAPTURE);

        return $matches[1][0];

    }//end getClassNameFromPhpDoc()


    private function setPropertyHelper(string $type, string $propertyName, mixed $value): void
    {
        if (method_exists($type, 'tryFrom')) {
            $this->$propertyName = $type::tryFrom($value);
            return;
        }

        $type = new $type();

        if ($type instanceof Model) {
            $this->$propertyName = new $type($value);
            return;
        }

    }//end setPropertyHelper()


    private function toArrayHelper(mixed $value): mixed
    {
        if (is_array($value)) {
            return $this->toArrayHelperArray($value);
        }

        if (! is_object($value)) {
            return $value;
        }

        if ($value instanceof Model) {
            return $value->toArray();
        }

        // @phpstan-ignore-next-line
        return method_exists($value, 'tryFrom') ? $value->value : null;

}//end toArrayHelper()


    /**
     * @param mixed[] $value
     * @return mixed[]
     * */
    private function toArrayHelperArray(array $value): array
    {
        $result = [];

        if (empty($value)) {
            return $result;
        }

        foreach ($value as $item) {
            $result[] = $this->toArrayHelper($item);
        }

        return $result;

    }//end toArrayHelperArray()


}//end class
