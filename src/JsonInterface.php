<?php

namespace One;

interface JsonInterface
{
    /**
     * convert collections as Json
     *
     * @return string
     */
    public function toJson();

    /**
     * create object from json stream string input
     * immutable, return new object instead the old one
     *
     * @param string $stream
     * @return self
     */
    public static function fromJson(string $stream);
}
