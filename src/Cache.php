<?php

namespace MonthlyCloud\Laravel;

use MonthlyCloud\Sdk\Cache\CacheInterface;
use Illuminate\Support\Facades\Cache as LaravelCache;

class Cache implements CacheInterface
{
	/**
	 * @var string
	 */
	protected $store;

	/**
	 * Constructor.
	 *
	 * @param string $store Cache store
	 * @return void
	 */
	public function __construct($store = null)
	{
		if (empty($store)) {
			$store = config('cache.default');
		}

		$this->store = $store;
	}

	/**
	 * Hash key to avoid issues with special chars.
	 *
	 * @param string $key 
	 * @return string
	 */
	public function hashKey($key)
	{
		return md5($key);
	}

    /**
     * Retrieve an item from the cache by key.
     *
     * @param  string|array  $key
     * @return mixed
     */
    public function get($key) {
		return LaravelCache::store($this->store)
			->get($this->hashKey($key));
    }

    /**
     * Store an item in the cache for a given number of seconds.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @param  int     $ttl
     * @return void
     */
    public function put($key, $value, $ttl = 0) {
		return LaravelCache::store($this->store)
			->put(
				$this->hashKey($key),
				$value,
				now()->addSeconds($ttl)
			);
    }

    /**
     * Remove an item from the cache.
     *
     * @param  string  $key
     * @return bool
     */
    public function forget($key) {
		return LaravelCache::store($this->store)
			->forget($this->hashKey($key));
    }

    /**
     * Check if item exists in cache.
     *
     * @param  string  $key
     * @return bool
     */
    public function has($key) {
		return LaravelCache::store($this->store)
			->forget($this->hashKey($key));
    }
}