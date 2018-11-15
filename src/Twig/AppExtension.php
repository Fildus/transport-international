<?php

namespace App\Twig;

use Behat\Transliterator\Transliterator;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('slug', [$this, 'toSlug']),
        ];
    }

    public function toSlug(string $string)
    {
        return str_replace(' ' ,'-' , Transliterator::unaccent(strtolower($string)));
    }
}