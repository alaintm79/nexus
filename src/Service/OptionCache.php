<?php

namespace App\Service;

use App\Entity\Blog\Opcion;
use Doctrine\Common\Cache\RedisCache;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class OptionCache{

    protected $em;
    protected $params;

    private const CACHE_ID = 'app_option_cache';

    public function __construct (EntityManagerInterface $em, ParameterBagInterface $params)
    {
        $this->em = $em;
        $this->params = $params;
    }

    /**
     * @inheritdoc
     */
    public function get()
    {
        $cache = new RedisCache();
        $cache->setRedis($this->redis());

        if(!$cache->contains(self::CACHE_ID)){
            $items = $this->convertToArray($this->em->getRepository(Opcion::class)->findAll());

            $cache->save(self::CACHE_ID, $items);

            return $items;
        }

        return $cache->fetch(self::CACHE_ID);
    }

    /**
     *  @inheritdoc
     */
    public function delete(){
        $cache = new RedisCache();
        $cache->setRedis($this->redis());

        if($cache->contains(self::CACHE_ID)){
            $cache->delete(self::CACHE_ID);
        }
    }

    /**
     *  @inheritdoc
     */
    public function convertToArray(array $opciones): ?array
    {
        $list = [];

        foreach($opciones as $item){
            $name = '';

            foreach($item as $key => $value){
                if($key === 'token'){
                    $list[$value]['nombre'] = $value;
                    $list[$value]['valor'] = $value;
                    $name = $value;
                }

                if($key === 'valor'){
                    $list[$name]['valor'] = $value;
                }
            }
        }

        return $list;
    }

    /**
     *  @inheritdoc
     */
    private function redis(){
        $redis = new \Redis();
        $redis->connect($this->params->get('app_redis_host'), $this->params->get('app_redis_port'));
        $redis->auth($this->params->get('app_redis_pass'));

        return $redis;
    }
}
