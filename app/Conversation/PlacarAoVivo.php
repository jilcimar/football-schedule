<?php

namespace App\Conversation;

use App\Models\Match;
use BotMan\BotMan\Messages\Conversations\Conversation;
use Carbon\Carbon;
use Weidner\Goutte\GoutteFacade;

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
                //Pegando os campos específicos
                $horario[$i] = $tr->filter('th')->filter('h4')->eq(0)->each(function ($th) {
                    return trim($th->text());
                });
                $tempo[$i] = $tr->filter('th')->filter('span')->eq(0)->each(function ($th) {
                    return trim($th->text());
                });
                $liga [$i] = $tr->filter('td')->filter('div')->each(function ($td) {
                    return trim($td->text());
                });
                //Eliminando os Campeonatos
                if( strpos($liga[$i][0], 'Série A') != false) {
                    $placarTime1 = explode(" ", $liga[$i][1])[0];
                    $placarTime2 = explode(" ", $liga[$i][2])[0];

                    $dados['time1'] = preg_replace('/[0-9]+/', '', $liga[$i][1]);
                    $dados['palcarTime1'] = isset($placarTime1)?$placarTime1:'-';
                    $dados['time2'] = preg_replace('/[0-9]+/', '', $liga[$i][2]);
                    $dados['palcarTime2'] = isset($placarTime2)?$placarTime2:'-';
                    $dados['hora'] = $horario[$i][0];
                    $dados['tempo'] = isset($tempo[$i][0])?$tempo[$i][0]:'';
                    $dados['canal'] = $liga[$i][3];
                    return $dados;
                }
            });

        $dados =  array_filter($dados);

        $date = Carbon::now()->format('d/m/Y');
        $firstPart = "\xF0\x9F\x9A\xA9	RESULTADOS AGORA - BRASILEIRÃO SÉRIE A ".$date."\n";


        foreach ($dados as $jogo) {
            $firstPart = $firstPart ."\n \xF0\x9F\x8F\x86 : " . $jogo['liga'] . "\n"
                . " \xE2\x9A\xBD : ". $jogo['time1'] .' '. $jogo['palcarTime1']." x ".
                    $jogo['palcarTime2'].' '.$jogo['time2'] ."\n"
                . " \xF0\x9F\x95\xA7 : ". $jogo['hora'].' - '.$jogo['tempo']."\n"
                . " \xF0\x9F\x93\xBA : " . $jogo['canal']. "\n"
                ."-------------------------------------------------------";
        }

        $this->say($firstPart);

        return true;
    }
}
