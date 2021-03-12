<?php

namespace App\Service;

class Notify{

    private $mailer;
    private $template;

    public function __construct (\Swift_Mailer $mailer, \Twig_Environment $template)
    {
        $this->mailer = $mailer;
        $this->template = $template;
        $this->path = __DIR__.'/../../../web/img';
    }

    /**
     * @inheritdoc
     */

    public function send($usuario, $body, $template, $subject = 'Notificación')
    {
        $message = new \Swift_Message();

        $message->setFrom('sysadmin@etep.une.cu')
            ->setTo($usuario)
            ->setSubject($subject)
            ->setBody($this->template->render($template, ['body' => $body]), 'text/html');

        $msgId = $message->getHeaders()->get('Message-ID');
        $msgId->setId(time() . '.' . uniqid('thing') . '@etep.une.cu');

        $this->mailer->send($message);
    }

    /**
     * @inheritdoc
     */

    public function sendList($usuarios, $body, $template, $subject = 'Notificación')
    {
        foreach ($usuarios as $usuario)
        {
            $message = new \Swift_Message();

            $message->setFrom('sysadmin@etep.une.cu')
                ->setTo($usuario['correo'])
                ->setSubject($subject)
                ->setBody($this->template->render($template, ['body' => $body]), 'text/html');

            $msgId = $message->getHeaders()->get('Message-ID');
            $msgId->setId(time() . '.' . uniqid('thing') . '@etep.une.cu');

            $this->mailer->send($message);
        }
    }

}
