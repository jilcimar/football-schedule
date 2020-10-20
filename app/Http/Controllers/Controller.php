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
        \Log::info("Cron executando!");

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

                $time [$i] = $tr->filter('td')->filter('span')->each(function ($td) {
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

                    $time1 = isset($time[$i][0]) ? $time[$i][0] :'';
                    $time2 = isset($time[$i][1]) ? $time[$i][1] :'';
                    $canal = isset($time[$i][2]) ? $time[$i][2] :'';

                    $dados['liga'] = $liga[$i][0];
                    $dados['time1'] = preg_replace('/[0-9]+/', '', $time1);
                    $dados['time2'] = preg_replace('/[0-9]+/', '', $time2);
                    $dados['hora'] = $horario[$i][0];
                    $dados['canal'] = $canal;
                    return $dados;
                }
            });

        $dados =  array_filter($dados);
        dd($dados);
    }

}
