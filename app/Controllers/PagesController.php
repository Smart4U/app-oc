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
     * @return ResponseInterface
     */
    public function home(): ResponseInterface {

        return new Response(200, [], (string)$this->renderer->render('front/pages/home.twig'));
    }

    /**
     * @return ResponseInterface
     */
    public function services(): ResponseInterface {
        return new Response(200, [], (string)$this->renderer->render('front/pages/services.twig'));
    }

    /**
     * @return ResponseInterface
     */
    public function about(): ResponseInterface {
        return new Response(200, [], (string)$this->renderer->render('front/pages/about.twig'));
    }

}