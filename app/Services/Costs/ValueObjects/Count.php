<?php

namespace App\Services\Costs\ValueObjects;

use Exception;
use JsonSerializable;

class Count implements JsonSerializable
{
    public readonly ?string $value;

    public readonly ?int $tokens;

    public function __construct(null|int|float $value = null, ?int $tokens = null)
    {
        $this->ensureValueIsValid($value);
        $this->value = is_null($value) ? $value : (string) $value;
        $this->tokens = $tokens; // Always initialize, even if it's to null
    }

    public function jsonSerialize(): ?string
    {
        if (is_null($this->value)) {
            return null;
        }

        $int = intval($this->value);
        $float = floatval($this->value);

        return $int == $float ? (string) $int : $this->value;
    }

    public function getTokens(): ?int
    {
        return $this->tokens;
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
