<?php

namespace App\Services\Agents\Content\Preset;

use Override;

class BuiltinPlaceholder implements PlaceholderInterface
{
    public string $type;

    public function __construct(public string $name)
    {
        $this->type = $this->name;
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->name,
            'is_builtin' => true
        ];
    }
}
