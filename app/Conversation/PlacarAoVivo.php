<?php

namespace App\Conversation;

use App\Models\Match;
use BotMan\BotMan\Messages\Conversations\Conversation;
use Carbon\Carbon;
use Weidner\Goutte\GoutteFacade;
use function Psy\debug;

class PlacarAoVivo extends Conversation
{
    public function run()
    {
        $this->initial();
    }

    public function initial()
    {
        $crawler = GoutteFacade::request('GET',
            'https://www.futebolnatv.com.br/');

        $dados = $crawler->filter('.table-bordered')
            ->eq(0)
            ->filter('tr[class="box"]')
            ->each(function ($tr, $i){

                $tempo[$i] = $tr->filter('th')->filter('div')->eq(0)->each(function ($th) {
                    return trim($th->text());
                });

                $horario[$i] = $tr->filter('th')->eq(0)->each(function ($th) {
                    return trim($th->text());
                });
                $liga [$i] = $tr->filter('td')->filter('div')->each(function ($td) {
                    return trim($td->text());
                });

                if( cleaningGames($liga[$i][0]) and isset($liga[$i][1]) and isset($liga[$i][2])
                    and isset($liga[$i][4])) {
                    $placarTime1 = explode(" ", $liga[$i][1])[0];
                    $placarTime2 = explode(" ", $liga[$i][2])[0];
                    $time1 =substr( $liga[$i][1],1);
                    $time2 =substr($liga[$i][2], 1);
                    $campeonato = $liga[$i][0];
                    $canal = $liga[$i][4];

                    $dados['liga'] = $campeonato;
                    $dados['time1'] = $time1;
                    $dados['palcarTime1'] = $placarTime1;
                    $dados['time2'] =$time2;
                    $dados['palcarTime2'] = $placarTime2;
                    $dados['hora'] = $horario[$i][0];
                    $dados['canal'] = $canal;
                    return $dados;
                }
            });
        try {
            $jogos = array_chunk($dados, 15);

            $date = Carbon::now()->format('d/m/Y');
            $firstPart = "\xF0\x9F\x9A\xA9 RESULTADOS  DOS JOGOS DE HOJE - ".$date."\n" .
                "(VERSÃO BETA)\n";

            foreach ($jogos[0] as $jogo) {
                if(isset($jogo)) {
                    $firstPart = $firstPart ."\n \xF0\x9F\x8F\x86 : " .  strtoupper($jogo['liga']) .
                        "\n \xE2\x9A\xBD : ". $jogo['time1'] .' '. $jogo['palcarTime1']." x ".
                        $jogo['palcarTime2'].' '.$jogo['time2'] ."\n"
                        . " \xF0\x9F\x95\xA7 : ". $jogo['hora']."\n"
                        . " \xF0\x9F\x93\xBA : " . $jogo['canal']. "\n"
                        ."-------------------------------------------------------";
                }
            }

            $this->say($firstPart);

            if(isset($jogos[1])) {
                $secondPart ='';
                foreach ($jogos[1] as $jogo) {
                    if(isset($jogo)) {
                        $secondPart = $secondPart ."\n \xF0\x9F\x8F\x86 : " .  strtoupper($jogo['liga']) .
                            "\n \xE2\x9A\xBD : ". $jogo['time1'] .' '. $jogo['palcarTime1']." x ".
                            $jogo['palcarTime2'].' '.$jogo['time2'] ."\n"
                            . " \xF0\x9F\x95\xA7 : ". $jogo['hora']."\n"
                            . " \xF0\x9F\x93\xBA : " . $jogo['canal']. "\n"
                            ."-------------------------------------------------------";
                    }
                }
                $this->say($secondPart);
            }

            if(isset($jogos[2])) {
                $thirdPart ='';
                foreach ($jogos[2] as $jogo) {
                    if(isset($jogo)) {
                        $thirdPart = $thirdPart ."\n \xF0\x9F\x8F\x86 : " .  strtoupper($jogo['liga']) .
                            "\n \xE2\x9A\xBD : ". $jogo['time1'] .' '. $jogo['palcarTime1']." x ".
                            $jogo['palcarTime2'].' '.$jogo['time2'] ."\n"
                            . " \xF0\x9F\x95\xA7 : ". $jogo['hora']."\n"
                            . " \xF0\x9F\x93\xBA : " . $jogo['canal']. "\n"
                            ."-------------------------------------------------------";
                    }
                }
                $this->say($thirdPart);
            }

        } catch (\Exception $exception) {
            logger()->debug($exception);
        }

        return true;
    }
}
