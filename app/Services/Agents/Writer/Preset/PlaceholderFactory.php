<?php

namespace App\Services\Agents\Writer\Preset;

class PlaceholderFactory
{
    private array $builtin = [
        'language',
        'tone',
        'programming_language'
    ];

    public function create(
        string $name,
        array $filters = []
    ): PlaceholderInterface {
        if (in_array($name, $this->builtin)) {
            return new BuiltinPlaceholder($name);
        }

        $type = Type::tryFrom($filters['type'] ?? 'text');

        $placeholder =  match ($type) {
            Type::TEXT => new TextPlaceholder($name),
            Type::ENUM => new EnumPlaceholder($name),
            default => throw new \InvalidArgumentException(
                "Unknown placeholder type: $type"
            ),
        };

        $this->applyFilters($placeholder, $filters);
        return $placeholder;
    }

    private function applyFilters(
        TextPlaceholder|EnumPlaceholder &$placeholder,
        array $filters
    ) {
        if (array_key_exists('label', $filters)) {
            $placeholder->label = $filters['label'];
        }

        if (array_key_exists('info', $filters)) {
            $placeholder->info = $filters['info'];
        }

        if (array_key_exists('value', $filters)) {
            $placeholder->value = $filters['value'];
        }

        if (array_key_exists('optional', $filters)) {
            $placeholder->required = false;
        }


        if ($placeholder instanceof TextPlaceholder) {
            $placeholder->multiline = isset($filters['multiline']);

            if (array_key_exists('placeholder', $filters)) {
                $placeholder->placeholder =
                    (string) $filters['placeholder'] ?: null;

                if ($placeholder->placeholder) {
                    $placeholder->placeholder = ucfirst(
                        $placeholder->placeholder
                    );
                }
            }
        }

        if ($placeholder instanceof EnumPlaceholder) {
            if (array_key_exists('options', $filters)) {
                $options = explode(',', $filters['options']);

                foreach ($options as $option) {
                    $placeholder->addOption($option);
                }
            }
        }
    }
}
