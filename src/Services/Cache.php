<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 19/11/18
 * Time: 21:24
 */

namespace App\Services;


use Psr\SimpleCache\CacheInterface;

class Cache
{
    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(CacheInterface $cache)
    {

        $this->cache = $cache;
    }


    public function get($key, callable $callback)
    {
        if ($key === true){
            return $callback;
        }
    }
}