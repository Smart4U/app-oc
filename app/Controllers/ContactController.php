<?php

namespace MyApp\Controllers;

use App\Core\Notify\Flash;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use App\Core\Controller\Controller;

/**
 * Class ContactController
 * @package MyApp\Controllers
 */
class ContactController extends Controller
{

    private $flash;

    public function __construct(Flash $flash)
    {
        $this->flash = $flash;
    }

    public function contact(): ResponseInterface {
        return new Response(200, [], 'contact');
    }

    public function postContact() {
        $this->flash->success('Merci pour votre message, nous vous répondrons dans les meilleurs délais.');
        return new Response(301, ['Location:' => '/contact'], 'contact');
    }


}