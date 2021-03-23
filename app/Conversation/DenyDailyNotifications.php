<?php

namespace App\Conversation;

use BotMan\BotMan\Messages\Conversations\Conversation;

class DenyDailyNotifications extends Conversation
{
    public function run()
    {
        $this->initial();
    }

    public function initial()
    {
        $text = "Pronto! Você não irá mais receber notificações diárias. \n Caso mude de ideia basta executar /ativarnotificacoes";

        $this->say($text);

        return true;
    }
}
