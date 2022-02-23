<?php

use Discord\Discord;
use Discord\Parts\Channel\Message;

class help
{
    public array $config;
    public Discord $discord;
    private Message $message;
    private array $triggers;
    
    public function init(array $config, Discord $discord)
    {
        $this->discord = $discord;
        $this->triggers[] = $this->config['bot']['trigger'] . 'help';
    }

    public function onMessage(array $iDataMsg, Message $message)
    {
        $this->message = $message;
        
        $message = $iDataMsg['message']['message'];
        $user = $iDataMsg['message']['author'];
        
        $this->message->reply("ta entrando aqui hein");
    }

    public function information()
    {
        return array(
            'name' => 'help',
            'trigger' => $this->triggers,
            'information' => 'Shows help for a command or all the commands available. Example **!help command**'
        );
    }

}