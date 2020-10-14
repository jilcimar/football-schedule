<?php

namespace App\Conversation;

use App\Models\Match;
use BotMan\BotMan\Messages\Conversations\Conversation;
use Carbon\Carbon;
use Weidner\Goutte\GoutteFacade;

class Tabela extends Conversation
{
    public function run()
    {
        $this->initial();
    }

    public function initial()
    {
        $crawler = GoutteFacade::request('GET',
            'https://www.terra.com.br/esportes/futebol/brasileiro-serie-a/tabela/');

        $dados = $crawler->filter('table')
            ->eq(0)
            ->filter('tbody')
            ->each(function ($tr, $i){
                //Pegando os campos específicos
                $posicao[$i] = $tr->filter('td[class="main position"]')->each(function ($th) {
                    return trim($th->text());
                });
                $time[$i] =  $tr->filter('td[class="main team-name"]')->each(function ($th) {
                    return trim($th->text());
                });
                $pontos[$i] =  $tr->filter('td[class="points"]')->each(function ($th) {
                    return trim($th->text());
                });

                $jogos[$i] =  $tr->filter('td[title="Jogos"]')->each(function ($th) {
                    return trim($th->text());
                });

                $dados['posicao'] = $posicao[$i];
                $dados['time'] = str_replace(">>","",$time[$i]);
                $dados['pontos'] = $pontos[$i];
                $dados['jogos'] =  $jogos[$i];
                return $dados;
            });

        $firstPart = "\xF0\x9F\x9A\xA9 CLASSIFICAÇÃO DO BRASILEIRÃO HOJE ".Carbon::now()->format('d/m/Y')."\n";

        foreach ($dados[0]["posicao"] as $key => $dado) {
            $firstPart = $firstPart."\n \x23\xE2\x83\xA3 : ".$dado."º \n"
                . " \xE2\x9A\xBD : ". $dados[0]['time'][$key] ."\n"
                . " \xF0\x9F\x8F\x86 : ".$dados[0]['pontos'][$key]." Pontos\n"
                . " \xF0\x9F\x8F\x81	: " . $dados[0]['jogos'][$key]. " Jogos\n"
                ."-------------------------------------------------------";
        }

       $this->say($firstPart);

        $this->say("\xE2\x9A\xBD  /jogosdehoje - Lista de jogos do dia\n\n".
            "\xF0\x9F\x93\x85  /jogosamanha - Lista de jogos de amanhã\n\n".
            "\xF0\x9F\x93\x88  /tabela - Tabela do Brasileirão Série A");

        return true;
    }
}
