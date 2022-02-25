<?php

use Discord\Discord;
use Discord\Parts\Channel\Message;

class avatar
{
    public array $config;
    public Discord $discord;
    private Message $message;
    private array $triggers;

    public function init(array $config, Discord $discord)
    {
        $this->config = $config;
        $this->discord = $discord;
        $this->triggers[] = $config['bot']['trigger'] . 'avatar';
    }

    public function onMessage(array $iDataMsg, Message $message)
    {
        $this->message = $message;
        $message = $iDataMsg['message']['message'];

        $data = command($message, $this->information()['trigger'], $this->config['bot']['trigger']);
        if(isset($data['trigger']))
        {
            global $commands;
            $messageString = $data['messageString'];

            if(!$messageString)
            {
                $this->message->reply($this->message->author->avatar);
            } else {
                $this->message->reply("EM PROGESSO!");
            }
        }
    }

    public function information()
    {
        return array(
            'name' => 'avatar',
            'trigger' => $this->triggers,
            'information' => 'Shows a Discord users avatar. If anyone user is send, return the avatar of the author. Example **!avatar @user**',
        );
    }
}