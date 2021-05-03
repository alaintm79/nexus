<?php

namespace App\Service;

use Twig\Environment;

class Notify{

    private $mailer;
    private const FROM = 'sysadmin@etep.une.cu';

    public function __construct (\Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * @inheritdoc
     */

    public function send($usuario, $text, $template, $subject = 'Notificación')
    {
        $message = (new \Swift_Message())
            ->setFrom(self::FROM)
            ->setTo($usuario)
            ->setSubject($subject)
            ->setBody(
                $this->twig->render(
                    $template, ['text' => $text]
                ),
                'text/html'
            );

        $this->mailer->send($message);
    }
}
