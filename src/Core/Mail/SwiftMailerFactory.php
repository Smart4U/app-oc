<?php

namespace App\Core\Mail;

use Psr\Container\ContainerInterface;
use Swift_Mailer;
use Swift_SmtpTransport;
use Swift_SendmailTransport;

/**
 * Class SwiftMailerFactory
 * @package App\Core\Mail
 */
class SwiftMailerFactory
{
    /**
     * @param ContainerInterface $container
     * @return Swift_Mailer
     */
    public function __invoke(ContainerInterface $container): Swift_Mailer
    {

        /*
        if($container->get('app')['app.env'] === 'dev') {
            $transport = new Swift_SmtpTransport('localhost', 1025);
        } elseif ($container->get('app')['app.env'] === 'test') {
            $transport = new Swift_SmtpTransport('localhost', 1025);
        }else {
            $transport = new Swift_SendmailTransport();
        }
        */
        $transport = new Swift_SmtpTransport('localhost', 1025);

        return new Swift_Mailer($transport);
    }
}
