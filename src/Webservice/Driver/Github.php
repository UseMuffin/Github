<?php
namespace Muffin\Github\Webservice\Driver;

use Github\Client;
use Github\HttpClient\CachedHttpClient;
use Muffin\Github\Webservice\Driver\Github\CacheBridge;
use Muffin\Webservice\AbstractDriver;

class Github extends AbstractDriver
{
    public $_defaultConfig = [
        'useCache' => null,
        'method' => null,
    ];

    /**
     * {@inheritdoc}
     *
     * @return void
     */
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

    /**
     * Handles the different supported authentication methods.
     *
     * @param array $credentials Github API credentials.
     * @return void
     */
    protected function _authenticate($credentials = [])
    {
        $this->_getMethod();
        $credentials += $this->config();

        if (!empty($this->_config['method'])) {
            $method = [$this->_client, 'authenticate'];
            switch ($this->_config['method']) {
                case Client::AUTH_HTTP_PASSWORD:
                    $args = [
                        $this->_config['username'],
                        $this->_config['password'],
                        $this->_config['method']
                    ];
                    break;
                case Client::AUTH_URL_CLIENT_ID:
                    $args = [
                        $this->_config['clientId'],
                        $this->_config['token'],
                        $this->_config['method']
                    ];
                    break;
                default:
                    $args = [
                        $this->_config['username'],
                        $this->_config['password'],
                        $this->_config['method']
                    ];
            }

            call_user_func_array($method, $args);
        }
    }

    /**
     * Sets the authentication method if not already done.
     *
     * @return void
     * @throws \RuntimeException If a password is provided but no username
     *   or if a secret is provided with no clientId.
     */
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
