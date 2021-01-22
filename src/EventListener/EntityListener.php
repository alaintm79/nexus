<?php

namespace App\EventListener;

use App\Entity\Sistema\AccessLog;
use App\Entity\Sistema\ActionLog;
use App\Service\LogRegister;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;


class EntityListener
{
    protected $security;
    protected $request;
    private $log;

    public function __construct(Security $security, RequestStack $request, LogRegister $log)
    {
        $this->security = $security;
        $this->request = $request;
        $this->log = $log;
    }

    /**
     * @inheritdoc
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof AccessLog) {
            return;
        }
        if ($entity instanceof ActionLog) {
            return;
        }
    }

    /**
     * @inheritdoc
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof AccessLog) {
            return;
        }
        if ($entity instanceof ActionLog) {
            return;
        }
    }

    /**
     * @inheritdoc
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof AccessLog) {
            return;
        }
        if ($entity instanceof ActionLog) {
            return;
        }

        $this->log->register('NEW');
    }

    /**
     * @inheritdoc
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof AccessLog) {
            return;
        }
        if ($entity instanceof ActionLog) {
            return;
        }

        $this->log->register('EDIT');
    }
}
