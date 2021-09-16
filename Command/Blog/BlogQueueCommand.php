<?php

namespace App\Command\Blog;

use App\Entity\Blog\Publicacion;
use App\Service\Cache;
use App\Service\Notify;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\RouterInterface;

class BlogQueueCommand extends Command
{
    protected static $defaultName = 'app:blog:queue';

    private $router;
    private $entityManagerInterface;
    private $params;
    private $notify;
    private $cache;

    private const EMAIL_TEMPLATE = 'blog/admin/notify.html.twig';
    private const CACHE_LATEST_ID = 'app_post_latest_cache';
    private const CACHE_RECOMMENDED_ID = 'app_post_recommended_cache';

    public function __construct(RouterInterface $router, EntityManagerInterface $entityManagerInterface, ParameterBagInterface $params, Notify $notify, Cache $cache)
    {
        parent::__construct();

        $this->router = $router;
        $this->entityManagerInterface = $entityManagerInterface;
        $this->params = $params;
        $this->notify = $notify;
        $this->cache = $cache;

    }

    protected function configure()
    {
        $this->setDescription('Blog \ Publicación de publicaciones en cola');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // $context = $this->router->getContext();
        // $context->setHost('www.etep.une.cu');
        // $context->setScheme('https');
        // $context->setBaseUrl('blog');

        $output->writeln([
            'Publicado publicaciones en cola',
            ''
        ]);

        $em = $this->entityManagerInterface;
        $queue = $em->getRepository(Publicacion::class)->findPublicacionesInQueue();

        foreach($queue as $post){
            $post->setEstado($em->getReference('App:Blog\Estado', 2));

            $this->notify->send($this->params->get('app_notify_blog'), $post, self::EMAIL_TEMPLATE);

            $post->setIsSent(\true);
        }

        $em->flush();

        if(\count($queue) > 0){
            $this->cache->deleteMultiple([self::CACHE_LATEST_ID, self::CACHE_RECOMMENDED_ID]);
        }

        $output->writeln('Publicadas: '.\count($queue).' publicaciones en cola');

        return 0;
    }
}
