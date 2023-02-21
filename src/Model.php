<?php

declare(strict_types=1);

namespace CastModels;

use ReflectionProperty;
use JsonSerializable;
use stdClass;
use ReflectionNamedType;
use ReflectionUnionType;
use ReflectionIntersectionType;

abstract class Model implements JsonSerializable
{
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
                $collection[] = new static($item);
            }
        }

        return $collection;
    }

    public function __construct(array|stdClass $data = [])
    {
        $this->update($data);
    }

    public function update(array|stdClass $data = [])
    {
        if (empty($data)) {
            return;
        }

        foreach ($data as $k => $v) {
            if (isset($v) && property_exists($this, $k)) {
                $this->setProperty($k, $v);
            }
        }
    }

    public function toArray(): array
    {
        $result = [];

        foreach (get_object_vars($this) as $property => $value) {
            $result[$property] = $this->toArrayHelper($value);
        }

        return $result;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function __toString()
    {
        return json_encode($this);
    }

    private function setProperty(string $propertyName, mixed $value)
    {
        $rProperty = new ReflectionProperty($this, $propertyName);
        $rType = $rProperty->getType();

        if ($rType->isBuiltin()) {
            $this->setPropertyBuiltIn($rProperty, $propertyName, $value);
        } else {
            $this->setPropertyHelper($rType, $propertyName, $value);
        }
    }

    private function setPropertyBuiltIn(ReflectionProperty $rProperty, string $propertyName, mixed $value)
    {
        if (is_object($value)) {
            $value = (array) $value;
        }

        if (is_array($value)) {
            $this->setArray($rProperty, $propertyName, $value);
        } else {
            $this->$propertyName = $value;
        }
    }

    private function setArray(ReflectionProperty $rProperty, string $propertyName, mixed $value)
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
    }

    private function getClassNameFromPhpDoc(string $phpDoc): string
    {
        $pattern = '/\s+([\w|\\\\]+)\s*\*/m';
        $matches = [];

        preg_match($pattern, $phpDoc, $matches, PREG_OFFSET_CAPTURE);

        return $matches[1][0];
    }

    private function setPropertyHelper(ReflectionNamedType|ReflectionUnionType|ReflectionIntersectionType $rType, string $propertyName, mixed $value)
    {
        $type = $rType->getName();

        if (method_exists($type, 'tryFrom')) {
            $this->$propertyName = $type::tryFrom($value);
            return;
        }

        $type = new $type();

        if ($type instanceof Model) {
            $this->$propertyName = new $type($value);
            return;
        }
    }

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

        if (method_exists($value, 'tryFrom')) {
            return $value->value;
        }
    }

    private function toArrayHelperArray(array $value): array
    {
        $result = [];

        if (empty($value)) {
            return $result;
        }

        foreach($value as $item) {
            $result[] = $this->toArrayHelper($item);
        }

        return $result;
    }
}
