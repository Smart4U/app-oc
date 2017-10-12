<?php

namespace App\Core\Twig;

use App\Core\Routing\Router;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap3View;

/**
 * Class PagerFantaTwigExtension
 * @package Core\Twig
 */
class PagerFantaTwigExtension extends \Twig_Extension
{

    /**
     * @var Router
     */
    private $router;

    /**
     * PagerFantaTwigExtension constructor.
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
            new \Twig_SimpleFunction('paginate', [$this, 'paginate'], ['is_safe' => ['html']])
        ];
    }

    /**
     * @param Pagerfanta $paginatedResults
     * @param string $route
     * @param array $queryArgs
     * @return string
     */
    public function paginate(Pagerfanta $paginatedResults, string $route, array $queryArgs = []) :string
    {
        $view = new TwitterBootstrap3View();
        return $view->render($paginatedResults, function (int $page) use ($route, $queryArgs) {
            if ($page > 1) {
                $queryArgs['p'] = $page;
            }
            return $this->router->getURI($route, [], $queryArgs);
        });
    }
}
