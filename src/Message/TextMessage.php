<?php

namespace EvansKim\NaverWorksBot\Message;

class TextMessage implements MessageContract
{
    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * @param  mixed  $message
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    public function getContent(): array
    {
        return [
            'type' => 'text',
            'text' => $this->message,
        ];
    }
}
