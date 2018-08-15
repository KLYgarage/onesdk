<?php declare(strict_types=1);

namespace One\Validator;

abstract class AbstractValidator implements ValidatorInterface
{
    /**
     * value
     * @var mixed
     */
    protected $value;

    /**
     * errorMessage
     * @var string|null
     */
    protected $errorMessage;

    /**
     * Default constructor
     * @param mixed $value
     * @param string|null $errorMessage
     */
    public function __construct($value = null, $errorMessage = null)
    {
        if (! empty($value)) {
            $this->value = $value;
        }
        if (! empty($value)) {
            $this->errorMessage = $errorMessage;
        }
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @param mixed $errorMessage
     */
    public function setErrorMessage($errorMessage = null): self
    {
        if (! empty($errorMessage) && is_string($errorMessage)) {
            $this->errorMessage = $errorMessage;
            return $this;
        }
        throw new \Exception('Supplied argument must be non empty string', 1);
    }

    /**
     * Reset error message
     */
    public function resetErrorMessage(): self
    {
        $this->errorMessage = null;

        return $this;
    }
}
