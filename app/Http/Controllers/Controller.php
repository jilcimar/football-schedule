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
            'https://www.futebolnatv.com.br/');

        $dados = $crawler->filter('.table-bordered')
            ->eq(0)
            ->filter('tr[class="box"]')
            ->each(function ($tr, $i){
                //Pegando os campos específicos
                $horario[$i] = $tr->filter('th')->filter('h4')->eq(0)->each(function ($th) {
                    return trim($th->text());
                });
                $tempo[$i] = $tr->filter('th')->filter('div')->eq(0)->each(function ($th) {
                    return trim($th->text());
                });

                $liga [$i] = $tr->filter('td')->filter('div')->each(function ($td) {
                    return trim($td->text());
                });

                $time [$i] = $tr->filter('td')->filter('span')->each(function ($td) {
                    return trim($td->text());
                });

                //Filtrando só série A
                if( strpos($liga[$i][0], 'Série A') != false ) {
                    $placarTime1 = explode(" ", $time[$i][1])[0];
                    $placarTime2 = explode(" ", $time[$i][2])[0];
                    $dados['time1'] = preg_replace('/[0-9]+/', '', $time[$i][0]);
                    $dados['palcarTime1'] = isset($placarTime1)?(int)$placarTime1:'-';
                    $dados['time2'] = preg_replace('/[0-9]+/', '', $time[$i][2]);
                    $dados['palcarTime2'] = isset($placarTime2)?(int)$placarTime2:'-';
                    $dados['hora'] = $horario[$i][0];
                    $dados['tempo'] = isset($tempo[$i][0])?' - '.$tempo[$i][0]:'';
                    $dados['canal'] = $time[$i][3];
                    return $dados;
                }
            });

        $dados =  array_filter($dados);


        dd($dados);
    }

}
