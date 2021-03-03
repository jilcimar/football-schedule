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

                $time [$i] = $tr->filter('td')->filter('span')->each(function ($td) {
                    return trim($td->text());
                });

                //Filtrando só série A
                if(cleaningGames($liga[$i][0])) {
                    $placarTime1 = explode(" ", $liga[$i][1])[0];
                    $placarTime2 = explode(" ", $liga[$i][2])[0];
                    $time1 =isset($liga[$i][1])?explode(" ", $liga[$i][1]):'';
                    $time2 = isset($liga[$i][1])?explode(" ", $liga[$i][1]):'';

                    $dados['time1'] = isset($time1[1])? $time1[1] : '';
                    $dados['palcarTime1'] = isset($placarTime1)?(int)$placarTime1:'-';
                    $dados['time2'] = isset($time2[2])? $time2[2] : '';
                    $dados['palcarTime2'] = isset($placarTime2)?(int)$placarTime2:'-';
                    $dados['hora'] = $horario[$i][0];
                    $dados['tempo'] = isset($tempo[$i][0])?' - '.$tempo[$i][0]:'';
                    $dados['canal'] = isset($liga[$i][4])? $liga[$i][4] : '';
                    return $dados;
                }
            });

        $dados =  array_filter($dados);

        dd($dados);
    }

}
