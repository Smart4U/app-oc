<?php

namespace App\Core\Twig;

/**
 * Class FrontHelperTwigExtension
 * @package App\Core\Twig
 */
class FrontHelperTwigExtension extends \Twig_Extension
{

    /**
     * @var array|false|string
     */
    private $domain;

    /**
     * @var string
     */
    private $manifest;


    /**
     * FrontHelperTwigExtension constructor.
     */
    public function __construct()
    {
        $this->domain = getenv('APP_DOMAIN');
        $this->manifest = file_get_contents(ROOT . '/public/dist/manifest.json');
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('style', [$this, 'style'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('stylePath', [$this, 'stylePath']),
            new \Twig_SimpleFunction('script', [$this, 'script'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('scriptPath', [$this, 'scriptPath'])
        ];
    }

    /**
     * @param string $file
     * @return string
     */
    public function style(string $file) :string
    {
        if (getenv('APP_ENV') !== 'dev') {
            $manifest = json_decode($this->manifest, true);
            return '<link rel="stylesheet" href="' . $this->domain . '/dist/' . $manifest[$file] . '" type="text/css" media="all"/>';
        }
        return '<link rel="stylesheet" href="' . $this->domain . '/dist/' . $file . '" type="text/css" media="all"/>';
    }


    /**
     * @param string $file
     * @return string
     */
    public function stylePath(string $file) :string
    {
        return $this->domain . '/dist/' . $file;
    }


    /**
     * @param string $file
     * @return string
     */
    public function script(string $file) :string
    {
        if (getenv('APP_ENV') !== 'dev') {
            $manifest = json_decode($this->manifest, true);
            return '<script type="text/javascript" src="' . $this->domain . '/dist/' . $manifest[$file] . '"></script>';
        }
        return '<script type="text/javascript" src="' . $this->domain . '/dist/' . $file . '"></script>';
    }


    /**
     * @param string $file
     * @return string
     */
    public function scriptPath(string $file) :string
    {
        return $this->domain . '/dist/' . $file;
    }
}
