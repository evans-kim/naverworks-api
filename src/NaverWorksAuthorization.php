<?php

namespace EvansKim\NaverWorksBot;

use Firebase\JWT\JWT;
use GuzzleHttp\Client;

class NaverWorksAuthorization
{
    /**
     * @var string
     */
    private $clientSecret;

    /**
     * @var string
     */
    private $authKey;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $serverId;

    private $privateKey;

    public static $trial = 0;

    private $cacheDir = '';
    private NaverWorksClient|null $nwClient;

    public function __construct($appId, $consumerKey, $serverId, $path)
    {
        $this->clientId = $appId;
        $this->clientSecret = $consumerKey;
        $this->serverId = $serverId;
        $this->privateKey = $path;

        $this->cacheDir = __DIR__.'/temp/';
        if(!is_dir($this->cacheDir) && !file_exists($this->cacheDir)){
            mkdir($this->cacheDir);
        }

        $this->nwClient = null;
    }

    public function createApiClient()
    {
        if(!$this->nwClient){
            $this->nwClient = new NaverWorksClient($this->getAccessToken());
        }
        return $this->nwClient;
    }

    public function clearAccessToken()
    {
        $path = $this->cacheDir.'/naverworks_access_token';
        if(file_exists($path)){
            unlink($path);
        }
    }

    public function tryAgain()
    {
        $this->clearAccessToken();
        static::$trial += 1;
        if (static::$trial < 2) {
            return $this->createApiClient();
        }

        return null;
    }

    private function getAccessToken()
    {
        if ($this->hasCachedToken()) {
            return $this->authKey;
        }

        $client = new Client([
            'headers' => [
                'Content-Type' => ' application/x-www-form-urlencoded',
            ],
        ]);

        $JWT = $this->generateJWT();

        $response = $client->post('https://auth.worksmobile.com/oauth2/v2.0/token', [
            'form_params' => [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $JWT,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'scope' => 'bot user',
            ],
        ]);
        $json = json_decode($response->getBody()->getContents());

        if (empty($json->access_token)) {
            throw new \Exception( 'private key 파일, env 을 확인해 주세요.');
        }
        $this->setBearer($json->access_token);

        return $json->access_token;
    }

    private function setBearer($token)
    {
        $this->authKey = $token;
        $this->setCache('naverworks_access_token', $token, 60*60*23);
    }

    private function generateJWT()
    {
        $key = file_get_contents($this->privateKey);

        $payload = [
            'iss' => $this->clientId,
            'sub' => $this->serverId,
            'iat' => time(),
            'exp' => time() + (60 * 60),
        ];

        return JWT::encode($payload, $key, 'RS256');
    }

    private function hasCachedToken(): bool
    {
        $token = $this->getCache('naverworks_access_token');
        if ($token) {
            $this->authKey = $token;

            return true;
        }

        return false;
    }
    private function getCache($key){
        $path = $this->cacheDir . $key;
        if(!file_exists($path)){
            return null;
        }
        $json = json_decode(file_get_contents($path));
        if($json->expired_at < time()){
            $this->clearAccessToken();
            return null;
        }
        return $json->token;
    }
    private function setCache(string $string, $token, int $int)
    {
        file_put_contents($this->cacheDir.$string, json_encode([
            'token' => $token,
            'expired_at' => time() + $int,
        ], JSON_UNESCAPED_SLASHES));
    }
}
