<?php

namespace App\Core\Controller;

use App\Core\Renderer\RendererInterface;

/**
 * Class Controller
 * @package App\Controller
 */
class Controller
{

    protected $renderer;

    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }
}
