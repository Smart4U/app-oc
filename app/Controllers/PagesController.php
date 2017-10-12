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

    public function home(): ResponseInterface {

        return new Response(200, [], (string)$this->renderer->render('pages/home.twig'));
    }


    public function about(): ResponseInterface {
        return new Response(200, [], 'about');
    }

}