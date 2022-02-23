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

$commandsDir = array('./src/commands/onMessage/*.php');
$commands = array();

foreach ($commandsDir as $dir) {
    foreach (glob($dir) as $command) {
        require_once $command;

        $filname = str_replace('.php', '', basename($command));
        $c = new $filname;
        $c->init($discord);
        $commands[]=$c;
    }
}

$discord = new Discord([
    'token' => $config['bot']['token'],
]);

$discord->on('ready', function (Discord $discord) use ($commands, $config) {
    echo "BotTestPHP is online..." . PHP_EOL;

    $discord->on(Event::MESSAGE_CREATE, function (Message $message, Discord $discord) use ($commands, $config) {
        
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
