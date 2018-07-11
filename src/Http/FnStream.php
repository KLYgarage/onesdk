<?php

namespace One\Http;

/**
 * @param string $_fn___toString Contains function name as value
 * @param string $_fn_close Contains function name as value
 * @param string $_fn_detach Contains function name as value
 * @param string $_fn_rewind Contains function name as value
 * @param string $_fn_getSize Contains function name as value
 * @param string $_fn_tell Contains function name as value
 * @param string $_fn_eof Contains function name as value
 * @param string $_fn_isSeekable Contains function name as value
 * @param string $_fn_seek Contains function name as value
 * @param string $_fn_isWritable Contains function name as value
 * @param string $_fn_write Contains function name as value
 * @param string $_fn_isReadable Contains function name as value
 * @param string $_fn_read Contains function name as value
 * @param string $_fn_getContents Contains function name as value
 * @param string $_fn_getMetadata Contains function name as value
 */
class FnStream implements \Psr\Http\Message\StreamInterface
{
    /** @var array */
    private $methods;
    /** @var array Methods that must be implemented in the given array */
    private static $slots = ['__toString', 'close', 'detach', 'rewind',
        'getSize', 'tell', 'eof', 'isSeekable', 'seek', 'isWritable', 'write',
        'isReadable', 'read', 'getContents', 'getMetadata'];
    /**
     * @param array $methods Hash of method name to a callable.
     */
    public function __construct(array $methods)
    {
        $this->methods = $methods;
        // Create the functions on the class
        foreach ($methods as $name => $fn) {
            $this->{'_fn_' . $name} = $fn;
        }
    }
    /**
     * Lazily determine which methods are not implemented.
     * @throws \BadMethodCallException
     */
    public function __get($name)
    {
        throw new \BadMethodCallException(str_replace('_fn_', '', $name)
            . '() is not implemented in the FnStream');
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
     * An unserialize would allow the __destruct to run when the unserialized value goes out of scope.
     * @throws \LogicException
     */
    public function __wakeup()
    {
        throw new \LogicException('FnStream should never be unserialized');
    }
    /**
     * Adds custom functionality to an underlying stream by intercepting
     * specific method calls.
     *
     * @param StreamInterface $stream  Stream to decorate
     * @param array           $methods Hash of method name to a closure
     *
     * @return FnStream
     */
    public static function decorate(\Psr\Http\Message\StreamInterface $stream, array $methods)
    {
        // If any of the required methods were not provided, then simply
        // proxy to the decorated stream.
        foreach (array_diff(self::$slots, array_keys($methods)) as $diff) {
            $methods[$diff] = [$stream, $diff];
        }
        return new self($methods);
    }
    public function __toString()
    {
        return call_user_func($this->_fn___toString);
    }
    public function close()
    {
        return call_user_func($this->_fn_close);
    }
    public function detach()
    {
        return call_user_func($this->_fn_detach);
    }
    public function getSize()
    {
        return call_user_func($this->_fn_getSize);
    }
    public function tell()
    {
        return call_user_func($this->_fn_tell);
    }
    public function eof()
    {
        return call_user_func($this->_fn_eof);
    }
    public function isSeekable()
    {
        return call_user_func($this->_fn_isSeekable);
    }
    public function rewind()
    {
        call_user_func($this->_fn_rewind);
    }
    public function seek($offset, $whence = SEEK_SET)
    {
        call_user_func($this->_fn_seek, $offset, $whence);
    }
    public function isWritable()
    {
        return call_user_func($this->_fn_isWritable);
    }
    public function write($string)
    {
        return call_user_func($this->_fn_write, $string);
    }
    public function isReadable()
    {
        return call_user_func($this->_fn_isReadable);
    }
    public function read($length)
    {
        return call_user_func($this->_fn_read, $length);
    }
    public function getContents()
    {
        return call_user_func($this->_fn_getContents);
    }
    public function getMetadata($key = null)
    {
        return call_user_func($this->_fn_getMetadata, $key);
    }
}
