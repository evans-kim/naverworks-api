<?php

namespace EvansKim\NaverWorksBot\Message;

class LinkMessage extends TextMessage
{
    protected $link;

    protected $linkText = '바로가기';

    public function getContent(): array
    {
        return $content = [
            'type' => 'link',
            'contentText' => $this->message,
            'linkText' => $this->linkText,
            'link' => $this->link,
        ];
    }

    /**
     * @param  mixed  $link
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @param  mixed  $linkText
     */
    public function setLinkText($linkText)
    {
        $this->linkText = $linkText;

        return $this;
    }
}
