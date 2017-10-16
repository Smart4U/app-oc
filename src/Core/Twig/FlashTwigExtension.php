<?php

namespace App\Core\Twig;

use App\Core\Notify\Flash;

/**
 * Class FlashTwigExtension
 * @package Core\Twig
 */
class FlashTwigExtension extends \Twig_Extension
{

    /**
     * @var Flash
     */
    private $flash;

    /**
     * FlashTwigExtension constructor.
     * @param Flash $flash
     */
    public function __construct(Flash $flash)
    {
        $this->flash = $flash;
    }

    /**
     * @return array
     */
    public function getFunctions() :array
    {
        return [
            new \Twig_SimpleFunction('flash', [$this, 'getFlash'])
        ];
    }

    /**
     * @param $type
     * @return mixed
     */
    public function getFlash($type)
    {
        return $this->flash->get($type);
    }
}
