<?php

namespace MyApp\Controllers;


use App\Core\Routing\Router;
use MyApp\Models\Post;
use App\Core\Database\Database;
use App\Core\Renderer\RendererInterface;
use App\Core\Controller\Controller;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class PostsController
 * @package MyApp\Controllers
 */
class PostsController extends Controller
{

    /**
     * @var RendererInterface
     */
    protected $renderer;


    /**
     * @var Post
     */
    protected $post;

    protected $router;

    protected $defaultMaxPerPage = 15;

    /**
     * PostsController constructor.
     * @param RendererInterface $renderer
     * @param Database $database
     */
    public function __construct(Router $router, RendererInterface $renderer, Database $database) {
        $this->post = new Post($database);
        $this->renderer = $renderer;
        $this->router = $router;
    }

    /**
     * @param ServerRequestInterface $request
     * @return string
     */
    public function index(ServerRequestInterface $request): string {
        $posts = $this->post->pagination($this->defaultMaxPerPage, $request->getQueryParams()['p'] ?? 1, 'ORDER BY updated_at DESC');

        return $this->renderer->render('front/posts/index.twig', compact('posts'));
    }

    /**
     * @param ServerRequestInterface $request
     * @return string
     */
    public function show(ServerRequestInterface $request): string {
        $post = $this->post->find($request->getAttribute('id'));


        return $this->renderer->render('front/posts/show.twig', compact('post'));
    }

    public function getLastPosts(int $nbrOfPost) {
        return $this->post->getLast($nbrOfPost);
    }
}