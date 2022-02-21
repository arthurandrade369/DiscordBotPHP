<?php

class help
{
    public $discord;
    private $message;
    
    public function init($discord)
    {
        $this->discord = $discord;
    }

    public function onMessage($message)
    {
        $this->message = $message;

        $this->message->reply("ta entrando aqui hein");
    }

}