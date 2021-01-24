<?php

namespace App\Service;

use App\Entity\Sistema\ActionLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;


class LogRegister{

    /** @var EntityManagerInterface $em */
    protected $em;

    /** @var Security $security */
    protected $security;

    /** @var RequestStack $request */
    protected $request;

    public function __construct (EntityManagerInterface $em, Security $security, RequestStack $requestStack)
    {
        $this->em = $em;
        $this->security = $security;
        $this->request = $requestStack;
    }

    /**
     * @inheritdoc
     */
    public function register($action): void
    {

        $log = new ActionLog();
        $info = $this->request->getCurrentRequest();

        if(null !== $info){
            $log->setUsername($this->security->getToken()->getUsername());
            $log->setAction($action);
            $log->setIp($info->getClientIp());
            $log->setUrl($info->getPathInfo());
        }

        $this->em->persist($log);
        $this->em->flush();
    }
}
