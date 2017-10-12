<?php

namespace App\Core\Renderer;

use Psr\Container\ContainerInterface;
use Twig\Extension\DebugExtension;

/**
 * Class TwigRendererFactory
 * @package App\Core\Renderer
 */
class TwigRendererFactory
{
    /**
     * @param ContainerInterface $container
     * @return TwigRenderer
     */
    public function __invoke(ContainerInterface $container): TwigRenderer
    {
        $defaultPath = $container->get('views.path');
        $loader = new \Twig_Loader_Filesystem($defaultPath);
        $twig = new \Twig_Environment($loader, ['debug' => true]);
        $twig->addExtension(new DebugExtension());
        if ($container->has('twig.extensions')) {
            foreach ($container->get('twig.extensions') as $extension) {
                $twig->addExtension($extension);
            }
        }
        return new TwigRenderer($twig);
    }
}