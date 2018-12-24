<?php

namespace App\Services;


use App\Repository\OptionsRepository;
use Psr\SimpleCache\CacheInterface;

class OptionsService
{
    const KEY = 'cache-option-264849';

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var OptionsRepository
     */
    private $optionsRepository;


    public function __construct(CacheInterface $cache, OptionsRepository $optionsRepository)
    {
        $this->cache = $cache;
        $this->optionsRepository = $optionsRepository;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function get(string $name)
    {
        if (!$this->cache->has(self::KEY . $name)) {
            $this->cache->set(self::KEY . $name, $this->optionsRepository->findOneByName($name), 3600);
        }
        return $this->cache->get(self::KEY . $name);
    }

    /**
     * @param $name
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getValue($name)
    {
        return json_decode($this->get($name)->getValue());
    }
}