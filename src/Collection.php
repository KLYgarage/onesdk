<?php

namespace One;

class Collection implements \ArrayAccess, \IteratorAggregate, \Countable, ToArrayInterface, JsonInterface
{
    protected $props;

    /**
     * constructor
     *
     * @param array $props
     */
    public function __construct($props = array())
    {
        $this->props = $props;
    }

    /**
     * ArrayAccess Implementations
     */

    /**
     * @inheritDoc
     */
    public function offsetExists($offset)
    {
        return isset($this->props[$offset]);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        return isset($this->props[$offset]) ? $this->props[$offset] : null;
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        $this->props[$offset] = $value;
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {
        unset($this->props[$offset]);
    }

    /**
     * IteratorAggregate Implementations
     */

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->props);
    }

    /**
     * Countable Implementations
     */

    /**
     * @inheritDoc
     */
    public function count()
    {
        return count($this->props);
    }

    /**
     * ToArrayInterface Implementations
     */

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        return $this->props;
    }

    /**
     * Json Implementations
     */

    /**
     * @inheritDoc
     */
    public function toJson()
    {
        return json_encode(
            $this->toArray()
        );
    }

    /**
     * @inheritDoc
     */
    public static function fromJson($stream)
    {
        $props = json_decode((string) $stream);

        if (!is_array($props)) {
            throw new \InvalidArgumentException('argument must be json formated, and could be decoded.');
        }

        return new self($props);
    }

    /**
     * get single value based on key
     *
     * @param string $key
     * @return mixed|null value of the requested key
     */

    public function get($key)
    {
        return isset($this->props[$key]) ? $this->props[$key] : null;
    }

    /**
     * set value of certain key on property cannot add new property, use add instead
     *
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function set($key, $value)
    {
        if (!isset($this->props[$key])) {
            throw new \Exception("Cannot add new property from set. Use add()");
        }

        $this->props[$key] = $value;

        return $this;
    }

    /**
     * addNew item on props
     *
     * @param string $key
     * @param mixed $value
     * @return self
     */
    private function addNew($key, $value)
    {
        $this->props[$key] = $value;
        return $this;
    }

    /**
     * add new child array
     *
     * @param string $key
     * @param mixed $value
     * @return self
     */
    private function addArray($key, $value)
    {
        $this->props[$key][] = $value;

        return $this;
    }

    /**
     * appending to already existing array
     *
     * @param string $key
     * @param mixed $value
     * @return self
     */
    private function appendToArray($key, $value)
    {
        $this->props[$key] = array($this->props[$key], $value);

        return $this;
    }

    /**
     * add new item to props
     *
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function add($key, $value)
    {
        if (!array_key_exists($key, $this->props)) {
            return $this->addNew($key, $value);
        }

        if (is_array($this->props[$key])) {
            return $this->addArray($key, $value);
        }

        return $this->appendToArray($key, $value);
    }

    /**
     * map each props item against the callback and return the resulting object
     *
     * @param \Closure $callback
     * @param optional parameter to be used as $context inside the callback
     * @return void
     */
    public function map(\Closure $callback, $context = array())
    {
        $collection = new static();

        foreach ($this->props as $key => $value) {
            $collection->add($key, $callback($value, $key, $context));
        }

        return $collection;
    }

    /**
     * filter the props againt rule on callback
     *
     * @param \Closure $callback
     * @return self with filtered properties
     */
    public function filter(\Closure $callback)
    {
        $collection = new static();

        foreach ($this->props as $key => $value) {
            if ($callback($value, $key)) {
                $collection->add($key, $value);
            }
        }

        return $collection;
    }
}
