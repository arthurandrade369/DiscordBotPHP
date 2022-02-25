<?php

use Discord\Discord;
use Discord\Helpers\Collection;
use Discord\Parts\Channel\Message;
use Discord\Parts\Embed\Embed;
use Discord\WebSockets\Event;
use Dotenv\Dotenv;

include 'vendor/autoload.php';

if(file_exists('config/config.php')){
    require_once 'config/config.php';
} else {
    die();
}

foreach (glob(__DIR__ . 'src/lib/*.php') as $lib)
{
    require_once $lib;
}

$discord = new Discord([
    'token' => $config['bot']['token'],
]);

$commandsDir = array('./src/commands/onMessage/*.php');
$commands = array();

foreach ($commandsDir as $dir) {
    foreach (glob($dir) as $command) {
        require_once $command;

        $filename = str_replace('.php', '', basename($command));
        $cmds = new $filename;
        $cmds->init($config, $discord);
        $commands[]=$cmds;
    }
}


$discord->on('ready', function (Discord $discord) use ($commands, $config) {
    echo "BotTestPHP is online..." . PHP_EOL;

    $discord->on(Event::MESSAGE_CREATE, function (Message $message) use ($commands, $config) {
        
        $iDataMsg = array(
            'message' => array(
                'timestamp' => $message->timestamp,
                'id' => $message->id,
                'message' => $message->content,
                'channelId' => $message->channel_id,
                'author' => $message->author->username,
                'authorId' => $message->author->id,
                'authorDiscriminator' => $message->author->discriminator,
                'authorAvatar' => $message->author->avatar,
            )
        );

        if (isset($message->content[0])){
            if($message->content[0] == $config['bot']['trigger']){
                foreach ($commands as $command){
                    try {
                        $command->onMessage($iDataMsg, $message);
                    } catch (Exception $e) {
                        echo ($e);
                    }
                }
            }
        }
    });
});

$discord->run();
