<?php declare(strict_types=1);

namespace One;

/**
 * Collection class
 *
 * @method mixed|null get(string $key)
 * @method self set(string $key, mixed $value)
 * @method self add(string $key, mixed $value)
 * @method self map(\Closure $callback, array $context)
 * @method self filter filter(\Closure $callback)
 */
class Collection implements \ArrayAccess, \IteratorAggregate, \Countable, ToArrayInterface, JsonInterface
{
    /**
     * Properties
     * @var array<mixed>
     */
    protected $props;

    /**
     * constructor
     */
    public function __construct(array $props = [])
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
    public function offsetSet($offset, $value): void
    {
        $this->props[$offset] = $value;
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset): void
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
    public function toArray(): array
    {
        return $this->props;
    }

    /**
     * Json Implementations
     */

    /**
     * @inheritDoc
     */
    public function toJson(): string
    {
        return json_encode(
            $this->toArray()
        );
    }

    /**
     * @inheritDoc
     */
    public static function fromJson($stream): JsonInterface
    {
        $props = json_decode((string) $stream);

        if (! is_array($props)) {
            throw new \InvalidArgumentException('argument must be json formated, and could be decoded.');
        }

        return new self($props);
    }

    /**
     * get single value based on key
     *
     * @return mixed|null value of the requested key
     */
    public function get(string $key)
    {
        return isset($this->props[$key]) ? $this->props[$key] : null;
    }

    /**
     * set value of certain key on property cannot add new property, use add instead
     *
     * @param mixed $value
     */
    public function set(string $key, $value): self
    {
        if (! isset($this->props[$key])) {
            throw new \Exception('Cannot add new property from set. Use add()');
        }

        $this->props[$key] = $value;

        return $this;
    }

    /**
     * add new item to props
     *
     * @param mixed $value
     */
    public function add(string $key, $value): self
    {
        if (! array_key_exists($key, $this->props)) {
            return $this->addNew($key, $value);
        }

        if (is_array($this->props[$key])) {
            return $this->addArray($key, $value);
        }

        return $this->appendToArray($key, $value);
    }

    /**
     * map each props item against the callback and return the resulting object
     * IMMUTABLE
     *
     * @param array|string $context parameter context to be used inside callback
     * @return self that already mapped. Return new clone
     */
    public function map(\Closure $callback, $context = []): self
    {
        $collection = new static();

        foreach ($this->props as $key => $value) {
            $collection->add($key, $callback($value, $key, $context));
        }

        return $collection;
    }

    /**
     * filter the props againt rule on callback
     * IMMUTABLE
     *
     * @return self with filtered properties
     */
    public function filter(\Closure $callback): self
    {
        $collection = new static();

        foreach ($this->props as $key => $value) {
            if ($callback($value, $key)) {
                $collection->add($key, $value);
            }
        }

        return $collection;
    }

    /**
     * addNew item on props
     *
     * @param mixed $value
     */
    private function addNew(string $key, $value): self
    {
        $this->props[$key] = $value;
        return $this;
    }

    /**
     * add new child array
     *
     * @param mixed $value
     */
    private function addArray(string $key, $value): self
    {
        $this->props[$key][] = $value;

        return $this;
    }

    /**
     * appending to already existing array
     *
     * @param mixed $value
     */
    private function appendToArray(string $key, $value): self
    {
        $this->props[$key] = [$this->props[$key], $value];

        return $this;
    }
}
