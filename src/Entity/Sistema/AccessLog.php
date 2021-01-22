<?php

namespace App\Entity\Sistema;

use App\Entity\Traits\LogTrait;
use App\Repository\Sistema\AccessLogRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="nexus.nx_access_log")
 * @ORM\Entity(repositoryClass=AccessLogRepository::class)
 */
class AccessLog
{
    use LogTrait;
}
