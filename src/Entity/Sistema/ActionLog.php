<?php

namespace App\Entity\Sistema;

use App\Entity\Traits\LogTrait;
use App\Repository\Sistema\ActionLogRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="nexus.nx_action_log")
 * @ORM\Entity(repositoryClass=ActionLogRepository::class)
 */
class ActionLog
{
    use LogTrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }
}
