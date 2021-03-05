<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Weidner\Goutte\GoutteFacade;

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

        $dados =  array_filter($dados);

        dd($dados);
    }

}
