<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ArraySumExtension extends AbstractExtension
{

    public function getFilters(): array
    {
        return [
            new TwigFilter('array_sum', [$this, 'arraySum']),
        ];
    }

    public function arraySum($array)
    {
        return array_sum($array);
    }
}
