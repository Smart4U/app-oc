<?php

namespace MyApp\Controllers;

use App\Core\Database\Database;
use App\Core\Database\QueryBuilder;
use App\Core\Renderer\RendererInterface;
use GuzzleHttp\Psr7\Response;
use MyApp\Models\Post;
use Psr\Http\Message\ResponseInterface;
use App\Core\Controller\Controller;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class PostsController
 * @package MyApp\Controllers
 */
class PostsController extends Controller
{

    /**
     * @var QueryBuilder
     */
    protected $QB;
    /**
     * @var RendererInterface
     */
    protected $renderer;

    /**
     * PostsController constructor.
     * @param RendererInterface $renderer
     * @param Database $database
     */
    public function __construct(RendererInterface $renderer, Database $database) {
        $this->QB = new QueryBuilder($database->getConnection());
        $this->renderer = $renderer;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function index(ServerRequestInterface $request): ResponseInterface {
        $posts = $this->QB->paginate('posts', 10, $request->getQueryParams()['p'] ?? 1)->getIterator();
        return new Response(200, [], (string)$this->renderer->render('front/posts/index.twig', compact($posts)));
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function show(ServerRequestInterface $request): ResponseInterface {
        $post = $this->QB->select()->from('posts')->where('id = 1')->model(Post::class)->all();
        $post = new $post->model($post->records);
        return new Response(200, [], 'front/posts/show.twig', compact($post));
    }

}