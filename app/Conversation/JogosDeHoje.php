<?php

namespace App\Conversation;

use App\Models\Match;
use BotMan\BotMan\Messages\Conversations\Conversation;
use Carbon\Carbon;
use Weidner\Goutte\GoutteFacade;

class JogosDeHoje extends Conversation
{
    public function run()
    {
        $this->initial();
    }

    public function initial()
    {
        $jogos = Match::where('today', true)->get()->chunk(15);

        if(count($jogos)==0) {
           $this->say("Sem jogos hoje! \n \xF0\x9F\x91\x89 /jogosamanha - Lista de jogos de amanhã");
        };

        $date = Carbon::now()->format('d/m/Y');
        $firstPart = "\xF0\x9F\x9A\xA9	 JOGOS DE HOJE ".$date."\n";

        foreach ($jogos[0] as $jogo) {
            $firstPart = $firstPart ."\n \xF0\x9F\x8F\x86 : " . $jogo->league->name . "\n"
                . " \xE2\x9A\xBD : ". $jogo->team1 . " x ". $jogo->team2 ."\n"
                . " \xF0\x9F\x95\xA7 : ".$jogo->horary."\n"
                . " \xF0\x9F\x93\xBA : " . $jogo->channels. "\n"
                ."-------------------------------------------------------";
        }

       $this->say($firstPart);

        //2 Mensagem
        if(isset($jogos[1])) {
            $secondPart ='';
            foreach ($jogos[1] as $jogo) {
                $secondPart = $secondPart ."\n \xF0\x9F\x8F\x86 : " . $jogo->league->name . "\n"
                    . " \xE2\x9A\xBD : ". $jogo->team1 . " x ". $jogo->team2 ."\n"
                    . " \xF0\x9F\x95\xA7 : ".$jogo->horary."\n"
                    . " \xF0\x9F\x93\xBA : " . $jogo->channels. "\n"
                    ."-------------------------------------------------------";
            }
           $this->say($secondPart);
        }

        //3 Mensagem
        if(isset($jogos[2])) {
            $thirdPart ='';
            foreach ($jogos[2] as $jogo) {
                $thirdPart = $thirdPart ."\n \xF0\x9F\x8F\x86 : " . $jogo->league->name . "\n"
                    . " \xE2\x9A\xBD : ". $jogo->team1 . " x ". $jogo->team2 ."\n"
                    . " \xF0\x9F\x95\xA7 : ".$jogo->horary."\n"
                    . " \xF0\x9F\x93\xBA : " . $jogo->channels. "\n"
                    ."-------------------------------------------------------";
            }
           $this->say($thirdPart);
        }

        return true;
    }
}
