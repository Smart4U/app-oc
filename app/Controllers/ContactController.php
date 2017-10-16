<?php

namespace MyApp\Controllers;

use GuzzleHttp\Psr7\Request;
use Swift_Mailer;
use Swift_Message;
use App\Core\Notify\Flash;
use App\Core\Validator\Validator;
use App\Core\Controller\Controller;
use App\Core\Renderer\RendererInterface;
use App\Core\Validator\Rules\ErrorValidator;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ContactController
 * @package MyApp\Controllers
 */
class ContactController extends Controller
{

    protected $mailTo = 'contact@netadn.com';

    protected $flash;

    protected $mailer;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container->get(RendererInterface::class));
        $this->flash = $container->get(Flash::class);
        $this->mailer = $container->get(Swift_Mailer::class);
    }


    public function index(ServerRequestInterface $request): string {
        $params = $request->getParsedBody();
        $errors = [];

        foreach ($params as $key => $param) {
            if($param instanceof ErrorValidator) {
                $errors[$key] = $param;
            } else {
                $attributes[$key] = $param;
            }
        }

        return $this->renderer->render('/front/contact/index.twig', ['errors' => $errors]);

    }

    public function send(ServerRequestInterface $request) {

        $params = $request->getParsedBody();

        $errors = (new Validator($request->getParsedBody($params)))
            ->notEmpty('firstName', 'lastName', 'email', 'phone', 'message')
            ->length('lastName', 2, 45)
            ->length('firstName', 2, 45)
            ->email('email')
            ->phone('phone')
            ->getErrors();

        if($errors) {
            $params = array_merge($params, $errors);
            $this->flash->error('Veuillez corriger les erreurs du formulaire de contact.');
            $request = $request->withParsedBody($params);
            return $this->index($request);
        }

        $message = (new Swift_Message('Contact : Formulaire de contact'))
            ->setFrom([$params['email']])
            ->setTo([$this->mailTo])
            ->setBody($params['message']);

        try{
            $this->mailer->send($message);
        }catch (\Exception $e) {
            die('Une erreur inattendue s\'est produite, veuillez réessayer ultérieurement.');
            exit();
        }

        $this->flash->success('Merci ! Votre message a bien été envoyé. Nous y répondrons dès que possible.');
        $this->redirectToForm($request);
    }

    private function redirectToForm(ServerRequestInterface $request) {
        $server = $request->getServerParams();
        if(isset($server['HTTP_REFERER'])){
            $referer = $server['HTTP_REFERER'];
            header('Location: ' . $referer . '#contact-form');exit();

        }
        header('Location: /contact#contact-form');exit();
    }


}