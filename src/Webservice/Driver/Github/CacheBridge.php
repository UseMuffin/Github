<?php
namespace Muffin\Github\Webservice\Driver\Github;

use Cake\Cache\Cache;
use Github\HttpClient\Cache\CacheInterface;
use Guzzle\Http\Message\Response;
use RuntimeException;

class CacheBridge implements CacheInterface
{
    protected $_engine;

    /**
     * Constructor
     *
     * @param string $alias Cache name alias.
     */
    public function __construct($alias)
    {
        $this->_engine = Cache::engine($alias);
    }

    /**
     * Checks if cache has given `$id`.
     *
     * @param string|int $id Cache key/id.
     * @return bool
     */
    public function has($id)
    {
        return (bool)$this->_engine->read($id);
    }

    /**
     * Returns the time a given `$id` was last modified.
     *
     * @param string|int $id Cache key/id.
     * @return int
     */
    public function getModifiedSince($id)
    {
        return $this->_engine->read($id . '.time');
    }

    /**
     * Returns the cache's ETag.
     *
     * @param string|int $id Cache key/id.
     * @return string
     */
    public function getETag($id)
    {
        return $this->_engine->read($id . '.etag');
    }

    /**
     * Returns value in cache for given `$id`.
     *
     * @param string|int $id Cache key/id.
     * @return mixed
     */
    public function get($id)
    {
        return unserialize($this->_engine->read($id));
    }

    /**
     * Sets a value in cache for a given `$id`.
     *
     * @param string|int $id Cache key/id.
     * @param \Guzzle\Http\Message\Response $response API's response.
     * @return void
     */
    public function set($id, Response $response)
    {
        $this->_engine->write($id, serialize($response));
        $this->_engine->write($id . '.etag', $response->getHeader('ETag'));
        $this->_engine->write($id . '.time', time());
    }
}
