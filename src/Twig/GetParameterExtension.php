<?php

namespace App\Twig;

use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class GetParameterExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_parameter', [$this, 'getParameter']),
        ];
    }

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function getParameter($parameter)
    {
        return $this->params->get($parameter);
    }

    public function getName()
    {
        return 'TwigExtensions';
    }
}
