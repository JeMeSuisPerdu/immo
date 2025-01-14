<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class Parameters extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('parametersNav', [$this, 'renderParameters'], ['is_safe' => ['html'], 'needs_environment' => true]),
        ];
    }

    public function renderParameters(\Twig\Environment $twig): string
    {
        return $twig->render('parameters/parameters.html.twig');
    }
}
