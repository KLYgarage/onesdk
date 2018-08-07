<?php declare(strict_types=1);

namespace One\Http;

use Psr\Http\Message\StreamInterface;

/**
 * @property string $_fn___toString Contains function name as value
 * @property string $_fn_close Contains function name as value
 * @property string $_fn_detach Contains function name as value
 * @property string $_fn_rewind Contains function name as value
 * @property string $_fn_getSize Contains function name as value
 * @property string $_fn_tell Contains function name as value
 * @property string $_fn_eof Contains function name as value
 * @property string $_fn_isSeekable Contains function name as value
 * @property string $_fn_seek Contains function name as value
 * @property string $_fn_isWritable Contains function name as value
 * @property string $_fn_write Contains function name as value
 * @property string $_fn_isReadable Contains function name as value
 * @property string $_fn_read Contains function name as value
 * @property string $_fn_getContents Contains function name as value
 * @property string $_fn_getMetadata Contains function name as value
 * @property string[] $slots An array that store list of function name
 */
class FnStream implements StreamInterface
{
    /**
     * Slots
     * @var array<string>
     */
    private static $slots = ['__toString', 'close', 'detach', 'rewind',
        'getSize', 'tell', 'eof', 'isSeekable', 'seek', 'isWritable', 'write',
        'isReadable', 'read', 'getContents', 'getMetadata', ];

    /**
     * @param array $methods Hash of method name to a callable.
     */
    public function __construct(array $methods)
    {
        // Create the functions on the class
        foreach ($methods as $name => $fn) {
            $this->{'_fn_' . $name} = $fn;
        }
    }

    /**
     * The close method is called on the underlying stream only if possible.
     */
    public function __destruct()
    {
        if (isset($this->_fn_close)) {
            call_user_func($this->_fn_close);
        }
    }

    /**
     * Lazily determine which methods are not implemented.
     * @throws \BadMethodCallException
     */
    public function __get(string $name): void
    {
        throw new \BadMethodCallException(str_replace('_fn_', '', $name)
            . '() is not implemented in the FnStream');
    }

    /**
     * An unserialize would allow the __destruct to run when the unserialized value goes out of scope.
     * @throws \LogicException
     */
    public function __wakeup(): void
    {
        throw new \LogicException('FnStream should never be unserialized');
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return call_user_func($this->_fn___toString);
    }

    /**
     * Adds custom functionality to an underlying stream by intercepting
     * specific method calls.
     *
     * @param StreamInterface $stream  Stream to decorate
     * @param array           $methods Hash of method name to a closure
     */
    public static function decorate(StreamInterface $stream, array $methods): self
    {
        // If any of the required methods were not provided, then simply
        // proxy to the decorated stream.
        foreach (array_diff(self::$slots, array_keys($methods)) as $diff) {
            $methods[$diff] = [$stream, $diff];
        }
        return new self($methods);
    }

    /**
     * @inheritDoc
     */
    public function close()
    {
        return call_user_func($this->_fn_close);
    }

    /**
     * @inheritDoc
     */
    public function detach()
    {
        return call_user_func($this->_fn_detach);
    }

    /**
     * @inheritDoc
     */
    public function getSize()
    {
        return call_user_func($this->_fn_getSize);
    }

    /**
     * @inheritDoc
     */
    public function tell()
    {
        return call_user_func($this->_fn_tell);
    }

    /**
     * @inheritDoc
     */
    public function eof()
    {
        return call_user_func($this->_fn_eof);
    }

    /**
     * @inheritDoc
     */
    public function isSeekable()
    {
        return call_user_func($this->_fn_isSeekable);
    }

    /**
     * @inheritDoc
     */
    public function rewind(): void
    {
        call_user_func($this->_fn_rewind);
    }

    /**
     * @inheritDoc
     */
    public function seek($offset, $whence = SEEK_SET): void
    {
        call_user_func($this->_fn_seek, $offset, $whence);
    }

    /**
     * @inheritDoc
     */
    public function isWritable()
    {
        return call_user_func($this->_fn_isWritable);
    }

    /**
     * @inheritDoc
     */
    public function write($string)
    {
        return call_user_func($this->_fn_write, $string);
    }

    /**
     * @inheritDoc
     */
    public function isReadable()
    {
        return call_user_func($this->_fn_isReadable);
    }

    /**
     * @inheritDoc
     */
    public function read($length)
    {
        return call_user_func($this->_fn_read, $length);
    }

    /**
     * @inheritDoc
     */
    public function getContents()
    {
        return call_user_func($this->_fn_getContents);
    }

    /**
     * @inheritDoc
     */
    public function getMetadata($key = null)
    {
        return call_user_func($this->_fn_getMetadata, $key);
    }
}
