<?php
namespace Muffin\Github\Webservice\Driver\Github;

use Cake\Cache\Cache;
use Github\HttpClient\Cache\CacheInterface;
use Guzzle\Http\Message\Response;
use RuntimeException;

class CacheBridge implements CacheInterface
{
    protected $_engine;

    public function __construct($alias)
    {
        $this->_engine = Cache::engine($alias);
    }

    public function has($id)
    {
        return (bool)$this->_engine->read($id);
    }

    public function getModifiedSince($id)
    {
        return $this->_engine->read($id . '.time');
    }

    public function getETag($id)
    {
        return $this->_engine->read($id . '.etag');
    }

    public function get($id)
    {
        return unserialize($this->_engine->read($id));
    }

    public function set($id, Response $response)
    {

        $this->_engine->write($id, serialize($response));
        $this->_engine->write($id . '.etag', $response->getHeader('ETag'));
        $this->_engine->write($id . '.time', time());
    }
}
