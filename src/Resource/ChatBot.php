<?php

namespace EvansKim\NaverWorksBot\Resource;

use EvansKim\NaverWorksBot\Message\TextMessage;
use EvansKim\NaverWorksBot\NaverWorksClient;

class ChatBot
{
    private NaverWorksClient $client;
    private mixed $to;
    private mixed $botId;

    public function __construct($botId, NaverWorksClient $client)
    {
        $this->botId = $botId;
        $this->client = $client;
    }

    public function to($account)
    {
        $this->to = $account;
    }

    public function sendMessageToUser($message)
    {
        if(is_string($message)){
            $message = new TextMessage($message);
        }
        return $this->client->post("/v1.0/bots/{$this->botId}/users/{$this->to}/messages", [
            'json' => [
                'content' => $message->getContent()
            ]
        ]);
    }
}
