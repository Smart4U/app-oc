<?php

namespace MyApp\Controllers;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;


/**
 * Class PagesController
 * @package MyApp\Controllers
 */
class PagesController
{

    public function home(): ResponseInterface {
        return new Response(200, [], 'homepage');
    }


    public function about(): ResponseInterface {
        return new Response(200, [], 'about');
    }

}