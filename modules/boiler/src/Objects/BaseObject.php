<?php

namespace Boiler\Objects;

use Illuminate\Contracts\Support\Arrayable;
use ReflectionClass;
use ReflectionProperty;
use stdClass;

class BaseObject implements Arrayable
{
 /**
     * @param stdClass|array $item
     */
    public function __construct(stdClass | array $item = [])
    {
        foreach ($this->propertyList() as $prop) {
            $this->$prop = is_array($item)
            ? $this->getArrayValue($item, $prop)
            : $this->getObjectValue($item, $prop);
        }
    }

    /**
     * Make
     *
     * @param stdClass|array $item
     *
     * @return static
     */
    public static function make(stdClass | array $item = []): static
    {
        return new static($item);
    }

    /**
     * Convert object or array of objects to array.
     *
     * @param mixed $input
     *
     * @return mixed
     */
    protected function convertToArray(mixed $input): mixed
    {
        if (is_array($input)) {
            return array_map([$this, 'convertToArray'], $input);
        }
        if ($input instanceof BaseObject) {
            return $input->toArray();
        }

        return $input;
    }

    /**
     * Convert class to array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return array_reduce(
            $this->propertyList(),
            fn($result, $prop) => array_merge($result, [
                $prop => $this->convertToArray($this->{$prop}),
            ]),
            []
        );
    }

    /**
     * List of class properties.
     *
     * @return array<string>
     */
    protected function propertyList(): array
    {
        return array_map(
            fn(ReflectionProperty $prop) => $prop->getName(),
            (new ReflectionClass($this))->getProperties()
        );
    }

    /**
     * Get object's value.
     *
     * @param stdClass $item
     * @param string $key
     *
     * @return mixed
     */
    protected function getObjectValue(stdClass $item, string $key): mixed
    {
        return property_exists($item, $key) ? $item->$key : null;
    }

    /**
     * Get array's value.
     *
     * @param array $item
     * @param string $key
     *
     * @return mixed
     */
    protected function getArrayValue(array $item, string $key): mixed
    {
        return array_key_exists($key, $item) ? $item[$key] : null;
    }
}
