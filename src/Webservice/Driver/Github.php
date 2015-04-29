<?php
namespace Muffin\Github\Webservice\Driver;

use Muffin\Webservice\AbstractDriver;
use Muffin\Github\Webservice\Driver\Github\CacheBridge;
use Github\Client;
use Github\HttpClient\CachedHttpClient;

class Github extends AbstractDriver
{
    public $_defaultConfig = [
        'useCache' => null,
        'method' => null,
    ];

    public function initialize()
    {
        $httpClient = null;
        if (!empty($this->_config['useCache'])) {
            if ($this->_config['useCache'] === true) {
                $this->_config['useCache'] = 'default';
            }
            $httpClient = new CachedHttpClient();
            $httpClient->setCache(new CacheBridge($this->_config['useCache']));
        }

        $this->_client = new Client($httpClient);
        $this->_authenticate();
    }

    protected function _authenticate($credentials = [])
    {
        $this->_getMethod();
        $credentials += $this->config();

        if (!empty($this->_config['method'])) {
            $method = [$this->_client, 'authenticate'];
            switch ($this->_config['method']) {
                case Client::AUTH_HTTP_PASSWORD:
                    return call_user_func_array($method, [
                        $this->_config['username'],
                        $this->_config['password'],
                        $this->_config['method']
                    ]);
                case Client::AUTH_URL_CLIENT_ID:
                    return call_user_func_array($method, [
                        $this->_config['clientId'],
                        $this->_config['token'],
                        $this->_config['method']
                    ]);
                default:
                    return call_user_func_array($method, [
                        $this->_config['username'],
                        $this->_config['password'],
                        $this->_config['method']
                    ]);
            }
        }
    }

    protected function _getMethod()
    {
        if (!empty($this->_config['method'])) {
            return;
        }

        if (array_key_exists('password', $this->_config)) {
            if (empty($this->_config['username'])) {
                throw new RuntimeException();
            }

            $this->_config['method'] = Client::AUTH_HTTP_PASSWORD;
            return;
        }

        if (array_key_exists('secret', $this->_config)) {
            if (empty($this->_config['clientId'])) {
                throw new RuntimeException();
            }

            $this->_config['method'] = Client::AUTH_URL_CLIENT_ID;
            return;
        }

        if (array_key_exists('token', $this->_config)) {
            $this->_config['method'] = Client::AUTH_HTTP_TOKEN;
        }

    }

}
