<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CountExtension extends AbstractExtension
{

    public function getFunctions(): array
    {
        return [
            new TwigFunction('count', [$this, 'countArray']),
        ];
    }

    public function countArray($array)
    {
        return count($array);
    }
}
