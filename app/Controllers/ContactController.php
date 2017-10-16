<?php

namespace MyApp\Controllers;

use App\Core\Controller\Controller;
use App\Core\Validator\Validator;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ContactController
 * @package MyApp\Controllers
 */
class ContactController extends Controller
{

    public function index(): ResponseInterface {
        return new Response(200, [], '
            <form method="post" action="/contact">
            <input type="text" name="email">
            <button type="submit²" ">sd</button>
</form>
        ');

    }

    public function send(ServerRequestInterface $request) {


        $v = new Validator($request->getParsedBody());
        $v->required('email');
        $v->email('email');

        if(!$v->isValid()) {
            die('redirect with errors');
        }
        die('send mail and redirect');
    }


}