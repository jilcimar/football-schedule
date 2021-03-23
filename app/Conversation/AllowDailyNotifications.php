<?php

namespace App\Conversation;

use BotMan\BotMan\Messages\Conversations\Conversation;

class AllowDailyNotifications extends Conversation
{
    public function run()
    {
        $this->initial();
    }

    public function initial()
    {
        $text = "Pronto! Agora você receberá notificações diárias.";

        $this->say($text);

        return true;
    }
}
