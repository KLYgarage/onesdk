<?php declare(strict_types=1);

namespace One\Validator;

interface ValidatorInterface
{
    /**
     * Validate
     */
    public function validate(): bool;
}
