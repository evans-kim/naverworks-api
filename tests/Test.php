<?php

use EvansKim\NaverWorksBot\NaverWorksAuthorization;
use EvansKim\NaverWorksBot\Resource\ChatBot;
use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    public function testAuth()
    {
        $path = __DIR__ . '/../';
        $dotenv = \Dotenv\Dotenv::createImmutable($path);
        $dotenv->load();

        $auth = new NaverWorksAuthorization(
            $_ENV['NAVERWORKS_CLIENT_ID'],
            $_ENV['NAVERWORKS_CLIENT_SECRET'],
            $_ENV['NAVERWORKS_SERVICE_ACCOUNT'],
            $path.$_ENV['NAVERWORKS_PRIVATE_KEY_PATH']
        );

        $bot = new ChatBot($_ENV['NAVERWORKS_BOT_ID'], $auth->createApiClient());
        $bot->to($_ENV['NAVERWORKS_TEST_USER_ID']);
        $bot->sendMessageToUser('test message');

        $this->assertTrue(true);
    }

    public function testThis()
    {
        dump(__DIR__);
    }
}
