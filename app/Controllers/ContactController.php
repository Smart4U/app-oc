<?php

namespace MyApp\Controllers;

use App\Core\Controller\Controller;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ContactController
 * @package MyApp\Controllers
 */
class ContactController extends Controller
{

    public function contact(): ResponseInterface {
        return new Response(200, [], 'contact');

    }


}