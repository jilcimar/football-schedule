<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
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
        $crawler = GoutteFacade::request('GET',
            'https://www.futebolnatv.com.br/');

        $dados = $crawler->filter('.table-bordered')
            ->eq(0)
            ->filter('tr[class="box"]')
            ->each(function ($tr, $i){
            //Pegando os campos específicos
            $horario[$i] = $tr->filter('th')->eq(0)->each(function ($th) {
                return trim($th->text());
            });
            $liga [$i] = $tr->filter('td')->filter('div')->each(function ($td) {
                return trim($td->text());
            });

            $dados['liga']= $liga[$i][0];
            $dados['time1']= preg_replace('/[0-9]+/', '', $liga[$i][1]);
            $dados['time2']= preg_replace('/[0-9]+/', '', $liga[$i][2]);
            $dados['hora']= $horario[$i][0];
            $dados['canal']= $liga[$i][3];
            return $dados;
        });

        $dadosAmanha = $crawler->filter('.table-bordered')
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
                    and strpos($liga[$i][0], 'Feminino') == false )
                {
                    $dados['liga']= $liga[$i][0];
                    $dados['time1']= preg_replace('/[0-9]+/', '', $liga[$i][1]);
                    $dados['time2']= preg_replace('/[0-9]+/', '', $liga[$i][2]);
                    $dados['hora']= $horario[$i][0];
                    $dados['canal']= $liga[$i][3];
                    return $dados;
                }
            });

        $dadosAmanha = array_filter($dadosAmanha);

        return \GuzzleHttp\json_encode(['JOGOS DE AMANHA'=> $dadosAmanha]);
    }

}
