<?php

namespace App\Services\Costs\ValueObjects;

use Exception;
use JsonSerializable;
use Override;

class Count implements JsonSerializable
{
    public readonly ?string $value;

    public function __construct(null|int|float $value = null)
    {
        $this->ensureValueIsValid($value);
        $this->value = is_null($value) ? $value : (string) $value;
    }

    #[Override]
    public function jsonSerialize(): ?string
    {
        if (is_null($this->value)) {
            return null;
        }

        $int = intval($this->value);
        $float = floatval($this->value);

        return $int == $float ? (string) $int : $this->value;
    }

    /**
     * @throws Exception 
     */
    private function ensureValueIsValid(null|int|float  $value): void
    {
        if (!is_null($value) && $value < 0) {
            throw new \Exception(sprintf(
                '<%s> does not allow the value <%s>. Value must greater than 0.',
                static::class,
                $value
            ));
        }
    }
}
