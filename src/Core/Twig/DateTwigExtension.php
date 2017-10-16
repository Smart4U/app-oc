<?php


namespace App\Core\Twig;
use Carbon\Carbon;

/**
 * Class DateTwigExtension
 * @package App\Core\Twig
 */
class DateTwigExtension extends \Twig_Extension
{


    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction('dateHumanize', [$this, 'dateHumanize'], [ 'is_safe' => ['html'] ])
        ];
    }


    /**
     * @param string $date
     * @param string $language
     * @return string
     */
    public function dateHumanize(string $date, string $language = 'fr'): string
    {
        Carbon::setLocale($language);
        return Carbon::createFromTimeStamp(strtotime($date))->diffForHumans();
    }
}