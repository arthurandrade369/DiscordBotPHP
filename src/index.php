<?php
use Discord\Discord;
use Discord\Helpers\Collection;
use Discord\Parts\Channel\Message;
use Discord\Parts\Embed\Embed;
use Discord\WebSockets\Event;
use Dotenv\Dotenv;

include 'vendor/autoload.php';

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
                ->setColor(0x27E343)
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
                ->setThumbnail('https://www.botecodigital.dev.br/wp-content/themes/boteco_v4/img/logob.png')
                ->setImage('https://ferramentas.botecodigital.dev.br/qrcode_generator/imagem.php?c=Um%20qr%20code&ec=QR_ECLEVEL_L&t=10');

            $message->channel->sendEmbed($embed);
        }

        
        if (str_contains($content, 'ajuda')) {
            if ($message->channel->id === "942971671927717929") {
                $message->channel->sendMessage("Estamos trabalhando em um menu de ajuda, agradecemos a compreensão!");
                return;
            }
            $message->channel->sendMessage("{$message->author} se estiver precisando de alguma ajuda poste no canal apropriado <#942971671927717929>");
        }
        
        if (str_contains($content, 'clean')) {
            $channel->getMessageHistory(['limit' => 100])->done(function (Collection $messages){
                foreach ($messages as $msg){
                    $msg->delete();
                }
            });
            
            $message->channel->sendMessage("Chat limpo");
        }
    });
});

$discord->run();
