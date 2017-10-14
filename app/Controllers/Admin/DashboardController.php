<?php


namespace MyApp\Controllers\Admin;


use App\Core\Controller\Controller;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class DashboardController
 * @package MyApp\Controllers\Admin
 */
class DashboardController extends Controller
{


    /**
     * @param ServerRequestInterface $request
     * @return string
     */
    public function dashboard(ServerRequestInterface $request)  {
        return $this->renderer->render('admin/dashboard.twig');
    }

}