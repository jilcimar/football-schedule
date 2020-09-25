<?php

namespace App\Conversation;

use BotMan\BotMan\Messages\Conversations\Conversation;
use Weidner\Goutte\GoutteFacade;

class JogosDeAmanha extends Conversation
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
            ->eq(1)
            ->filter('tr[class="box"]')
            ->each(function ($tr, $i){
                //Pegando os campos específicos
                $horario[$i] = $tr->filter('th')->eq(0)->each(function ($th) {
                    return trim($th->text());
                });
                $liga [$i] = $tr->filter('td')->filter('div')->each(function ($td) {
                    return trim($td->text());
                });
                //Eliminando os Campeonatos
                if( strpos($liga[$i][0], 'Russo') == false
                    and strpos($liga[$i][0], 'Bielorrusso') == false
                    and strpos($liga[$i][0], 'Série B') == false
                    and strpos($liga[$i][0], 'Série C') == false
                    and strpos($liga[$i][0], 'Série D') == false
                    and strpos($liga[$i][0], 'Sub-20') == false
                    and strpos($liga[$i][0], 'A3') == false
                    and strpos($liga[$i][0], '2ª') == false
                    and strpos($liga[$i][0], 'MX') == false
                    and strpos($liga[$i][0], 'Feminino') == false ) {

                    $dados['liga'] = $liga[$i][0];
                    $dados['time1'] = preg_replace('/[0-9]+/', '', $liga[$i][1]);
                    $dados['time2'] = preg_replace('/[0-9]+/', '', $liga[$i][2]);
                    $dados['hora'] = $horario[$i][0];
                    $dados['canal'] = $liga[$i][3];
                    return $dados;
                }
            });

        $dados =  array_filter($dados);

        //Dividindo os dados para envio devido uma limitação no tamanho da mensagem
        $jogos = array_chunk($dados, 15);

        $firstPart = "\xF0\x9F\x9A\xA9	 JOGOS DE HOJE"."\n";

        foreach ($jogos[0] as $jogo) {
            $firstPart = $firstPart ."\n \xF0\x9F\x8F\x86 : " . $jogo['liga'] . "\n"
                . " \xE2\x9A\xBD : ". $jogo['time1'] . " x ". $jogo['time2'] ."\n"
                . " \xF0\x9F\x95\xA7 : ". $jogo['hora']."\n"
                . " \xF0\x9F\x93\xBA : " . $jogo['canal']. "\n"
                ."-------------------------------------------------------";
        }

        $this->say($firstPart);

        //2 Mensagem
        if(isset($jogos[1])) {
            $secondPart ='';
            foreach ($jogos[1] as $jogo) {
                $secondPart = $secondPart ."\n \xF0\x9F\x8F\x86 : " . $jogo['liga'] . "\n"
                    . " \xE2\x9A\xBD : ". $jogo['time1'] . " x ". $jogo['time2'] ."\n"
                    . " \xF0\x9F\x95\xA7 : ". $jogo['hora']."\n"
                    . " \xF0\x9F\x93\xBA : " . $jogo['canal']. "\n"
                    ."-------------------------------------------------------";
            }

            $this->say($secondPart);

        }

        //3 Mensagem
        if(isset($jogos[2])) {
            $thirdPart ='';
            foreach ($jogos[2] as $jogo) {
                $thirdPart = $thirdPart ."\n \xF0\x9F\x8F\x86 : " . $jogo['liga'] . "\n"
                    . " \xE2\x9A\xBD : ". $jogo['time1'] . " x ". $jogo['time2'] ."\n"
                    . " \xF0\x9F\x95\xA7 : ". $jogo['hora']."\n"
                    . " \xF0\x9F\x93\xBA : " . $jogo['canal']. "\n"
                    ."-------------------------------------------------------";
            }

            $this->say($thirdPart);

        }

        $this->say("\xF0\x9F\x91\x89  /jogosdehoje - Para ver a lista de jogos do dia\n".
            "\xF0\x9F\x91\x89  /jogosamanha - Para ver a lista de jogos de amanhã");

        return true;
    }
}
