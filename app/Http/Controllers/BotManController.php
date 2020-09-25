<?php

namespace App\Http\Controllers;

use App\Conversation\JogosDeAmanha;
use App\Conversation\JogosDeHoje;
use App\Conversation\StartConversation;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Telegram\TelegramDriver;


class BotManController extends Controller
{
    /**
     * @var BotMan $bot
     */
    public $bot;

    public function setUp()
    {
        $config = [
            "telegram" => [
                "token" => '1253599841:AAGAkEoAaVrGfExEAOaVmU-p0XpZM0kRGoc',
            ]
        ];

        DriverManager::loadDriver(TelegramDriver::class);

        $this->bot = BotManFactory::create($config);
    }

    public function __construct()
    {
        $this->setUp();
    }

    public function handle()
    {
        $this->bot->hears('/start', function (BotMan $bot) {
            $bot->startConversation(new StartConversation);
        });

        $this->bot->hears('/jogosdehoje', function (BotMan $bot) {
            $bot->startConversation(new JogosDeHoje);
        });

        $this->bot->hears('/jogosamanha', function (BotMan $bot) {
            $bot->startConversation(new JogosDeAmanha);
        });

        $this->bot->listen();
    }
}
