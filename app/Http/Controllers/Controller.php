<?php

namespace App\Http\Controllers;

use App\Models\Match;
use App\Models\Subscriber;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Weidner\Goutte\GoutteFacade;
use Telegram\Bot\Laravel\Facades\Telegram;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getDados()
    {
        $jogos = Match::where('today', true)->get()->chunk(15);

        if(count($jogos)==0) {
            $this->sendMessage("Sem jogos hoje! \n \xF0\x9F\x91\x89 /jogosamanha - Lista de jogos de amanhã");
        };

        $date = Carbon::now()->format('d/m/Y');
        $firstPart = "\xF0\x9F\x9A\xA9	 JOGOS DE HOJE ".$date."\n";
        dd($jogos[1], $jogos);
        foreach ($jogos[0] as $jogo) {
            $firstPart = $firstPart ."\n \xF0\x9F\x8F\x86 : " . $jogo['liga'] . "\n"
                . " \xE2\x9A\xBD : ". $jogo['time1'] . " x ". $jogo['time2'] ."\n"
                . " \xF0\x9F\x95\xA7 : ". $jogo['hora']."\n"
                . " \xF0\x9F\x93\xBA : " . $jogo['canal']. "\n"
                ."-------------------------------------------------------";
        }
    }

}
