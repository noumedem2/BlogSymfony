<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CutTextExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        
        return [
            new TwigFunction('CutText', [$this, 'CutText']),
        ];
    }

    public function CutText(string $texte, int $numberSentence): string
    {
        return  $texte;
    }
}
