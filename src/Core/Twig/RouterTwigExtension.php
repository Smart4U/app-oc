<?php

namespace App\Core\Twig;

use App\Core\Routing\Router;

/**
 * Class RouterTwigExtension
 * @package Core\Twig
 */
class RouterTwigExtension extends \Twig_Extension
{

    /**
     * @var Router
     */
    private $router;

    /**
     * RouterTwigExtension constructor.
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('path', [$this, 'pathFor'])
        ];
    }

    /**
     * @param string $path
     * @param array $params
     * @return string
     */
    public function pathFor(string $path, array $params = []) :string
    {
        return $this->router->getURI($path, $params);
    }

}
