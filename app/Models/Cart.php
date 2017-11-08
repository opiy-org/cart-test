<?php
/**
 * Created by PhpStorm.
 * User: opiy
 * Date: 04.11.2017
 * Time: 9:52
 */

namespace App\Models;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class Cart
{

    /**
     * @var string $cache_key
     */
    protected $cache_key;

    /**
     * @var int $cache_lifetime
     */
    protected $cache_lifetime;

    /**
     * @var array $items
     */
    protected $items;

    /**
     * Cart constructor.
     * @param string $user_token
     */
    public function __construct(string $user_token)
    {
        $this->items = [];
        $this->cache_key = 'cart_' . $user_token;
        $this->cache_lifetime = Config::get('cart.cache_lifetime',5);
    }


    /**
     * Get cart items
     *
     * @return array
     */
    public function getItems()
    {
        $cached_items = Cache::get($this->cache_key, false);
        if ($cached_items !== false) {
            $this->items = $cached_items;
        }

        return $this->items;
    }

    /**
     * Set cart items
     *
     * @param array $items
     */
    public function setItems(array $items)
    {
        $this->items = $items;
        Cache::put($this->cache_key, $this->items, $this->cache_lifetime);
    }

}