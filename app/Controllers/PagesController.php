<?php

namespace MyApp\Controllers;

use GuzzleHttp\Psr7\Response;
use App\Core\Controller\Controller;
use Psr\Http\Message\ResponseInterface;


/**
 * Class PagesController
 * @package MyApp\Controllers
 */
class PagesController extends Controller
{

    /**
     * @return string
     */
    public function home(): string {

        return $this->renderer->render('front/pages/home.twig');
    }

    /**
     * @return string
     */
    public function services(): string {

        return $this->renderer->render('front/pages/services.twig');
    }


    /**
     * @return string
     */
    public function about(): string {
        return $this->renderer->render('front/pages/about.twig');
    }

}