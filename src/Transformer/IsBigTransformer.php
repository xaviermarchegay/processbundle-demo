<?php

declare(strict_types=1);

namespace App\Transformer;

use CleverAge\ProcessBundle\Transformer\ConfigurableTransformerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IsBigTransformer implements ConfigurableTransformerInterface
{
    public function transform($value, array $options = []): int
    {
        if ($value === 0 || $value === null) {
            return 0;
        }

        return $value > 1_000 ? 1 : 0;
    }

    public function getCode(): string
    {
        return 'is_big';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
    }
}
