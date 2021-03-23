<?php

namespace App\Http\Controllers;

use App\Conversation\AllowDailyNotifications;
use App\Conversation\DenyDailyNotifications;
use App\Conversation\JogosDeAmanha;
use App\Conversation\JogosDeHoje;
use App\Conversation\PlacarAoVivo;
use App\Conversation\StartConversation;
use App\Models\Subscriber;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\Users\User;
use BotMan\Drivers\Telegram\TelegramDriver;


class BotManController extends Controller
{
    /**
     * @var BotMan $bot
     */
    public $bot;
    public $user;

    public function setUp()
    {
        $config = [
            "telegram" => [
                "token" => env('TELEGRAM_BOT_TOKEN'),
            ]
        ];

        DriverManager::loadDriver(TelegramDriver::class);

        $this->bot = BotManFactory::create($config);
        $this->user = new User();
    }

    public function __construct()
    {
        $this->setUp();
    }

    public function handle()
    {
        //START DO BOT
        $this->bot->hears('/start', function (BotMan $bot) {
            try {
                $user = $this->bot->getUser();
                Subscriber::updateOrCreate(
                    [
                        'chat_id' => $user->getId(),
                    ],
                    [
                        'chat_id' =>  $user->getId(),
                        'username' =>$user->getUsername(),
                        'first_name' => $user->getFirstName(),
                    ]
                );
                $bot->startConversation(new StartConversation);
            } catch (\Exception $e) {
                logger()->debug($e);
            }
        });

        //JOGOS DE HOJE
        $this->bot->hears('/jogosdehoje', function (BotMan $bot) {
            try {
                $user = $this->bot->getUser();
                Subscriber::updateOrCreate(
                    [
                        'chat_id' => $user->getId(),
                    ],
                    [
                        'chat_id' =>  $user->getId(),
                        'username' =>$user->getUsername(),
                        'first_name' => $user->getFirstName(),
                    ]
                );
                $bot->startConversation(new JogosDeHoje);
            } catch (\Exception $e) {
                logger()->debug($e);

            }
        });

        $this->bot->hears('/jogosdehoje@futebolnatv_bot', function (BotMan $bot) {
            $bot->startConversation(new JogosDeHoje);
        });

        //JOGOS DE AMANHÃ
        $this->bot->hears('/jogosamanha', function (BotMan $bot) {
            try {
                $user = $this->bot->getUser();
                Subscriber::updateOrCreate(
                    [
                        'chat_id' => $user->getId(),
                    ],
                    [
                        'chat_id' =>  $user->getId(),
                        'username' =>$user->getUsername(),
                        'first_name' => $user->getFirstName(),
                    ]
                );
                $bot->startConversation(new JogosDeAmanha);
            } catch (\Exception $e) {
                logger()->debug($e);
            }
        });

        $this->bot->hears('/jogosamanha@futebolnatv_bot', function (BotMan $bot) {
            $bot->startConversation(new JogosDeAmanha);
        });

        //PLACAR AO VIVO DOS JOGOS DA SÉRIE A
        $this->bot->hears('/placar', function (BotMan $bot) {
            $bot->startConversation(new PlacarAoVivo);
        });
        $this->bot->hears('/placar@futebolnatv_bot', function (BotMan $bot) {
            $bot->startConversation(new PlacarAoVivo);
        });

        //NOTIFICAÇÕES
        $this->bot->hears('/ativarnotificacoes', function (BotMan $bot) {
            try {
                $user = $this->bot->getUser();
                Subscriber::updateOrCreate(
                    [
                        'chat_id' => $user->getId(),
                    ],
                    [
                        'chat_id' =>  $user->getId(),
                        'username' =>$user->getUsername(),
                        'first_name' => $user->getFirstName(),
                        'daily_notification' => true,
                    ]
                );
                $bot->startConversation(new AllowDailyNotifications);
            } catch (\Exception $e) {
                logger()->debug($e);
            }
        });

        //DESATIVAR NOTIFICAÇÕES
        $this->bot->hears('/desativarnotificacoes', function (BotMan $bot) {
            try {
                $user = $this->bot->getUser();
                Subscriber::updateOrCreate(
                    [
                        'chat_id' => $user->getId(),
                    ],
                    [
                        'chat_id' =>  $user->getId(),
                        'username' =>$user->getUsername(),
                        'first_name' => $user->getFirstName(),
                        'daily_notification' => false,
                    ]
                );
                $bot->startConversation(new DenyDailyNotifications);
            } catch (\Exception $e) {
                logger()->debug($e);
            }
        });

        $this->bot->listen();
    }
}
