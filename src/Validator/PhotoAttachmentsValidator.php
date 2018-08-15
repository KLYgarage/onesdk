<?php declare(strict_types=1);

namespace One\Validator;

use One\Model\Photo;

class PhotoAttachmentsValidator extends AbstractValidator
{
    /**
     * Check if photo has
     * certain ratio
     * @return self|void
     * @throws \Exception
     */
    public function checkHasRatio(string $ratio, bool $throwException = false)
    {
        foreach ($this->value as $item) {
            if (! empty($item->offsetGet('ratio'))
                && $item->offsetGet('ratio') === $ratio
            ) {
                return $this;
            }
        }

        $this->setErrorMessage("Doesn't have vertical which is " . $ratio . ' ratio');

        if ($throwException) {
            throw new \Exception($this->getErrorMessage(), 1);
        }
    }

    /**
     * @inheritDoc
     **/
    public function validate(): bool
    {
        return empty($this->errorMessage) ? true : false;
    }
}
