<?php

use Discord\Builders\Components\Button;
use Discord\Builders\Components\TextInput;
use Discord\Discord;
use Discord\Helpers\Collection;
use Discord\Parts\Channel\Message;
use Discord\Parts\Embed\Embed;
use Discord\WebSockets\Event;
use Dotenv\Dotenv;

include 'vendor/autoload.php';

const BLUE = 0x0000FF;
const RED = 0xFF0000;
const GREEN = 0x00FF00;

$dotenv = Dotenv::createImmutable(dirname(__FILE__,2));
$dotenv->load();

$discord = new Discord([
    'token' => $_ENV['KEY'],
]);

$discord->on('ready', function (Discord $discord) {
    echo "BotTestPHP is online..." . PHP_EOL;

    $discord->on(Event::MESSAGE_CREATE, function (Message $message, Discord $discord) {
        
        if ($message->author->id === $discord->id) return;
        
        $channel = $discord->getChannel($message->channel->id);

        $content = $message->content;
        $control = strpos($content, '!');
        if($control !== 0) return;

        if (str_contains($content,'embed')) {
            $embed = new Embed($discord);
            $embed->setTitle('Uma mensagem embed')
                ->setDescription("O campo descrição!")
                ->setFooter('Conteudo do rodapé')
                ->setColor(RED)
                ->addField([
                    'name' => "Campo 1:",
                    'value' => 'Valor campo 1',
                    'inline' => false,
                ])
                ->addField([
                    'name' => 'Campo 2',
                    'value' => "Valor Campo 2",
                    'inline' => false,
                ])
                ->setThumbnail('https://thypix.com/wp-content/uploads/2021/02/pixel-sunglasses-17-700x407.png')
                ->setImage('https://upload.wikimedia.org/wikipedia/commons/thumb/a/a9/Illuminati_triangle_eye.png/461px-Illuminati_triangle_eye.png');

            $message->channel->sendEmbed($embed);
            return;
        }

        
        if (str_contains($content, 'ajuda')) {
            if ($message->channel->id === "942971671927717929") {
                $message->channel->sendMessage("Estamos trabalhando em um menu de ajuda, agradecemos a compreensão!");
                return;
            }
            $message->channel->sendMessage("{$message->author} se estiver precisando de alguma ajuda poste no canal apropriado <#942971671927717929>");
            return;
        }
        
        if (str_contains($content, 'clean')) {
            $channel->getMessageHistory(['limit' => 100])->done(function (Collection $messages){
                foreach ($messages as $msg){
                    $msg->delete();
                }
            });
            
            $message->channel->sendMessage("Chat limpo");
            return;
        }
    });
});

$discord->run();
