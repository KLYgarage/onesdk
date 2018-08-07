<?php declare(strict_types=1);

namespace One;

/**
 * JSON usage interface
 *
 * @method string toJson()
 * @method self fromJson(string $stream)
 */
interface JsonInterface
{
    /**
     * convert collections as Json
     */
    public function toJson(): string;

    /**
     * create object from json stream string input
     * immutable, return new object instead the old one
     */
    public static function fromJson(string $stream): self;
}
