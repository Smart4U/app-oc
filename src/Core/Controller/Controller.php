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

    protected $messages = [
        'store.success' => "L'élément a bien été enregistré",
        'store.error' => "Une ou plusieurs erreur(s) ont été détéctée(s)",

        'update.success' => "L'élément a bien été modifié",
        'update.error' => "L'élément n'a pas été mis à jour, une ou plusieurs ont été détéctée(s)",

        'delete.success' => "L'élément a bien été supprimé",
    ];

    protected $defaultMaxPerPage = 10;

    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }
}
