<?php

namespace MyApp\Controllers\Admin;


use MyApp\Models\Post;
use App\Core\Validator\Validator;
use App\Core\Validator\Rules\ErrorValidator;

use App\Core\Notify\Flash;
use App\Core\Routing\Router;
use App\Core\Controller\Controller;
use App\Core\Database\Database;
use App\Core\Renderer\RendererInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class PostsAdminController
 * @package MyApp\Controllers\Admin
 */
class PostsAdminController extends Controller
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Post
     */
    protected $post;
    /**
     * @var Flash|mixed
     */
    protected $flash;
    /**
     * @var Router|mixed
     */
    protected $router;
    /**
     * @var RendererInterface|mixed
     */
    protected $renderer;
    /**
     * @var
     */
    protected $request;
    /**
     * @var int
     */
    protected $defaultMaxPerPage = 10;
    /**
     * @var string
     */
    protected $defaultRedirect = 'admin.posts.index';

    /**
     * PostsAdminController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
        $this->post = new Post($this->container->get(Database::class));
        $this->renderer = $this->container->get(RendererInterface::class);
        $this->flash = $this->container->get(Flash::class);
        $this->router = $this->container->get(Router::class);
    }


    /**
     * List of articles with pagination
     *
     * @param ServerRequestInterface $request
     * @return string
     */
    public function index(ServerRequestInterface $request)  {
        $items = $this->post->pagination($this->defaultMaxPerPage, $request->getQueryParams()['p'] ?? 1);

        return $this->renderer->render('admin/posts/index.twig', compact('items'));
    }

    /**
     * Add a new article
     *
     * @param ServerRequestInterface $request
     * @return string
     */
    public function create(ServerRequestInterface $request) {

        $errors = [];
        $attributes = [];
        foreach ($request->getParsedBody() as $key => $params) {
            if($params instanceof ErrorValidator) {
                $errors[$key] = $params;
            } else {
                $attributes[$key] = $params;
            }
        }

        return $this->renderer->render('admin/posts/create.twig', ['item' => $attributes, 'errors' => $errors]);
    }


    /**
     * Save new article in database
     *
     * @param ServerRequestInterface $request
     * @return string|void
     */
    public function store(ServerRequestInterface $request) {

        $params = $request->getParsedBody();
        $errors = $this->post->create(new Validator($params), $params);

        if($errors) {
            $params = array_merge($params, $errors);
            $this->flash->error($this->messages['store.error']);
            $request = $request->withParsedBody($params);

            return $this->create($request);
        }

        $this->flash->success($this->messages['store.success']);

        return $this->router->redirect('admin.posts.index');
    }


    /**
     * Read an article
     *
     * @param ServerRequestInterface $request
     * @return string
     */
    public function show(ServerRequestInterface $request) {
        $item = $this->post->find($request->getAttribute('id'));

        return $this->renderer->render('admin/posts/show.twig', compact('item'));
    }


    /**
     * Edit an article
     *
     * @param ServerRequestInterface $request
     * @return string
     */
    public function edit(ServerRequestInterface $request) {

        $item = $this->post->find( $request->getAttribute('id'));

        if(!$item) {
            $this->router->redirect('admin.posts.index');
        }

        $errors = [];
        $attributes = ['id' => $item->id];
        foreach ($request->getParsedBody() as $key => $params) {
            if($params instanceof ErrorValidator) {
                $errors[$key] = $params;
            } else {
                $attributes[$key] = $params;
            }
        }

        return $this->renderer->render('admin/posts/edit.twig', ['item' => $item, 'errors' => $errors]);
    }

    /**
     * @param ServerRequestInterface $request
     * @return string|void
     */
    public function update(ServerRequestInterface $request) {

        $params = ['id' => $request->getAttribute('id')];
        $params = array_merge($params, $request->getParsedBody());
        $errors = $this->post->update(new Validator($params), $params);

        if($errors) {
            $params = array_merge($params, $errors);
            $this->flash->error($this->messages['update.error']);
            $request = $request->withParsedBody($params);

            return $this->edit($request);
        }

        $this->flash->success($this->messages['store.success']);
        return $this->router->redirect('admin.posts.index');
    }


    /**
     * Delete an article
     *
     * @param ServerRequestInterface $request
     */
    public function destroy(ServerRequestInterface $request) {
        $this->post->delete($request->getAttribute('id'));
        $this->flash->success($this->messages['delete.success']);

        return $this->router->redirect($this->defaultRedirect);
    }

}