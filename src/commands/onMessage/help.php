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
        $this->config = $config;
        $this->discord = $discord;
        $this->triggers[] = $this->config['bot']['trigger'] . 'help';
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
                $cmd = array();
                foreach($commands as $command)
                {
                    $info = $command->information();
                    if(!empty($info['name']))
                    {
                        $cmd[] = $info['name'];
                    }
                }

                $this->message->reply('Here is a list of commands available: **' . 
                implode('** |  **', $cmd) . 
                "** If you'd like help with a specific command simply use the command !help <CommandName>");
            } else {
                foreach($commands as $command)
                {
                    if(strtolower($messageString) === strtolower($command->information()['name']))
                    {
                        $this->message->reply($command->information()['information']);
                    }
                }
            }

        }
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