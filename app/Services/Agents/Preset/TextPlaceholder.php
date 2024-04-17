<?php

namespace App\Services\Agents\Preset;

use Override;

class TextPlaceholder extends AbstractPlaceholder implements
    PlaceholderInterface
{
    public bool $multiline = false;
    public ?string $placeholder = null;

    public function __construct(string $name)
    {
        parent::__construct($name, Type::TEXT);
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return array_merge(
            $this->toArray(),
            [
                'multiline' => $this->multiline,
                'placeholder' => $this->placeholder,
            ]
        );
    }
}
