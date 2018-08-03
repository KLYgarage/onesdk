<?php declare(strict_types=1);

namespace One;

/**
 * Interface to facilitate array conversion on object
 *
 * @method array toArray()
 */
interface ToArrayInterface
{
    /**
     * toArray function
     */
    public function toArray(): array;
}
