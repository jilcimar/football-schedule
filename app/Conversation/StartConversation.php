<?php

namespace App\Conversation;

use BotMan\BotMan\Messages\Conversations\Conversation;

class StartConversation extends Conversation
{
    public function run()
    {
        $this->initial();
    }

    public function initial()
    {
        $text =
            "\xE2\x9A\xBD Futebol na TV! \n\n".
            "Aqui você terá acesso:\n\n".
            "\xF0\x9F\x93\x85 A lista de todos os jogos do dia das principais ligas nacionais e mundiais \n\n".
            "\xF0\x9F\x93\xBA Horário e local da transmissão de cada jogo \n\n".
            "\xF0\x9F\x91\x89  /jogosdehoje - Para ver a lista de jogos do dia\n".
            "\xF0\x9F\x91\x89  /jogosamanha - Lista de jogos de amanhã";
        $this->say($text);
        return true;
    }
}
