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
use function GuzzleHttp\Psr7\str;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getDados()
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
        dd($firstPart);
    }

}
