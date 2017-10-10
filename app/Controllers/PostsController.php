<?php

namespace MyApp\Controllers;

use App\Core\Database\Database;
use App\Core\Database\QueryBuilder;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use App\Core\Controller\Controller;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class PostsController
 * @package MyApp\Controllers
 */
class PostsController extends Controller
{

    private $QB;

    public function __construct(Database $database) {
        $this->QB = new QueryBuilder($database->getConnection());
    }

    public function index(ServerRequestInterface $request): ResponseInterface {
        $posts = $this->QB->paginate('posts', 10, $request->getQueryParams()['p'] ?? 1)->getIterator();
        return new Response(200, [], 'blog index');
    }

    public function show(ServerRequestInterface $request){
        $post = $this->QB->select()->from('posts')->where('id = :id')->params(['id' => $request->getAttributes()['id']])->execute();
        return new Response(200, [], 'show article');
    }

}