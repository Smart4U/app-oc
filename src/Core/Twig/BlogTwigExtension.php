<?php

namespace App\Core\Twig;


class BlogTwigExtension extends \Twig_Extension
{


    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('increment', [$this, 'increment']),
            new \Twig_SimpleFunction('decrement', [$this, 'decrement'])
        ];
    }


    public function increment(int $value): int {
        return $value + 1;
    }

    public function decrement(int $value): int  {
        return $value - 1;
    }

}