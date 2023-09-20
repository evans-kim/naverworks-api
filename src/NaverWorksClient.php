<?php

namespace EvansKim\NaverWorksBot;

use GuzzleHttp\Client;

/**
 * @property-read Client client
 */
class NaverWorksClient
{
    private $apiId;

    protected $requestUrl;

    /**
     * @var Client
     */
    private $_client;

    public function __call($name, $arguments)
    {
        if (in_array($name, ['post', 'delete', 'get', 'put'])) {
            return $this->_client->{$name}(...$arguments);
        }

        return $this->{$name}(...$arguments);
    }

    public function __construct($token, $baseUri = 'https://www.worksapis.com')
    {
        $this->_client = new Client([
            'base_uri' => $baseUri,
            //'verify' => false,
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$token,
            ],
        ]);
    }

    public function getApiId()
    {
        return $this->apiId;
    }

    /**
     * @param  mixed  $apiId
     */
    public function setApiId($apiId): void
    {
        $this->apiId = $apiId;
    }

    public function getClient(): Client
    {
        return $this->_client;
    }
}
