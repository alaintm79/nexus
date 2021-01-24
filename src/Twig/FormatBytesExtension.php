<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class FormatBytesExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('formatBytes', [$this, 'formatBytes']),
        ];
    }

    public function formatBytes($bytes, $precision = 2)
    {
        $size = ['B','KB','MB','GB','TB','PB','EB','ZB','YB'];
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$precision}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }
}
